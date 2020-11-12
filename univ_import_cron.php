<?php
/*************************************
*				Universal Import/Export			 *
*			   Command Line Interface			 *
**************************************
A log file is created into /system/logs/universal_import_cron.log

*************************************/

// Opencart version - set here your opencart version - you can find it in footer once logged in admin
define('VERSION', '2.3.0.2');

// Path to your admin folder - generally admin/
define('GKD_ADMIN_PATH', 'admin/');

if (!empty($argv)) {
  array_shift($argv);
  foreach ($argv as $param) {
    parse_str($param, $vars);
    foreach ($vars as $key => $val) {
      $_GET[$key] = $val;
    }
  }
} else if (empty($_GET)) {
  die('No parameter found');
}
/*************    DO NOT EDIT BELOW THIS LINE    *************/


define('GKD_CRON', true);

set_time_limit(36000);

// set missing vars
$_SERVER['SERVER_PORT'] = 80;

// Configuration
if (file_exists(GKD_ADMIN_PATH.'config.php')) {
	require_once(GKD_ADMIN_PATH.'config.php');
} else if (file_exists( str_replace('\'', '/', realpath(dirname(__FILE__))) .'/'. GKD_ADMIN_PATH.'config.php')) {
	require_once(str_replace('\'', '/', realpath(dirname(__FILE__))) .'/'. GKD_ADMIN_PATH.'config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	die('Incorrect GKD_ADMIN_PATH');
}


//VirtualQMOD
if (is_file(GKD_ADMIN_PATH.'../vqmod/vqmod.php')) {
  require_once(GKD_ADMIN_PATH.'../vqmod/vqmod.php');
  VQMod::bootup();
  $_vqmod = true;

  // VQMODDED Startup
  require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));
} else {
  require_once(DIR_SYSTEM . 'startup.php');
}

// OC 2.2
if (version_compare(VERSION, '2.2', '>=')) {
  // Registry
  $registry = new Registry();

  // Loader
  $loader = new Loader($registry);
  $registry->set('load', $loader);

  // Config
  $config = new Config();
  $config->load('default');
  $config->load('admin');
  $registry->set('config', $config);

  // Request
  $registry->set('request', new Request());

  // Response
  $response = new Response();
  $response->addHeader('Content-Type: text/html; charset=utf-8');
  $registry->set('response', $response);

  if (version_compare(VERSION, '3', '>=')) {
    // Database
    if ($config->get('db_autostart')) {
      $registry->set('db', new DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port')));
    }
    
    // Session
    if ($config->get('session_autostart')) {
      $session = new Session($config->get('session_engine'), $registry);
      $session->start();
      $registry->set('session', $session);
    }
    
    // Cache 
    $registry->set('cache', new Cache($config->get('cache_engine'), $config->get('cache_expire')));
  } else {
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
  }

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
      if (version_compare(VERSION, '3', '>=')) {
        foreach ($value as $priority => $action) {
          $event->register($key, new Action($action), $priority);
        }
      } else {
        $event->register($key, new Action($value));
      }
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

  // Front Controller
  if (version_compare(VERSION, '3', '>=')) {
    $controller = new Router($registry);
  } else {
    $controller = new Front($registry);
  }
  // default: 
  //  'startup/startup' 
  //  'startup/error' 
  //  'startup/event' 
  //  'startup/sass' 
  //  'startup/login' 
  //  'startup/permission' 

  // Pre Actions
  $pre_actions = array('startup/startup');
  foreach ($pre_actions as $value) {
    $controller->addPreAction(new Action($value));
  }
  
  // if ($config->has('action_pre_action')) {
    // foreach ($config->get('action_pre_action') as $value) {
      // $controller->addPreAction(new Action($value));
    // }
  // }

  // Settings
  $db = $registry->get('db');
  $query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

  foreach ($query->rows as $result) {
    if (!$result['serialized']) {
      $config->set($result['key'], $result['value']);
    } else if (version_compare(VERSION, '2.1', '>=')) {
      $config->set($result['key'], json_decode($result['value'], true));
    } else {
      $config->set($result['key'], unserialize($result['value']));
    }
  }
  
  // Dispatch
  $controller->dispatch(new Action('module/universal_import/cron'), new Action($config->get('action_error')));

  // Output
  //$response->setCompression($config->get('config_compression'));
  $response->output();
  
  
} else {
// OC 1.5 - 2.1
  
  // Registry
  $registry = new Registry();

  // Loader
  $loader = new Loader($registry);
  $registry->set('load', $loader);

  // Config
  $config = new Config();
  $registry->set('config', $config);

  // Database
  $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
  $registry->set('db', $db);

  // Settings
  $query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

  foreach ($query->rows as $result) {
    if (!$result['serialized']) {
      $config->set($result['key'], $result['value']);
    } else if (substr(VERSION, 0, 3) == '2.1') {
      $config->set($result['key'], json_decode($result['value'], true));
    } else {
      $config->set($result['key'], unserialize($result['value']));
    }
  }

  // Url
  $url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);	
  $registry->set('url', $url);

  if (version_compare(VERSION, '2', '>=')) {
    // Event
    $event = new Event($registry);
    $registry->set('event', $event);

    // Event Register
    if ($config->has('action_event')) {
      foreach ($config->get('action_event') as $key => $value) {
        $event->register($key, new Action($value));
      }
    }
  }
  
  // Log
  $log = new Log($config->get('config_error_filename'));
  $registry->set('log', $log);

  function error_handler($errno, $errstr, $errfile, $errline) {
    global $log, $config;
    
    switch ($errno) {
      case E_NOTICE:
      case E_USER_NOTICE:
        $error = 'Notice';
        break;
      case E_WARNING:
      case E_USER_WARNING:
        $error = 'Warning';
        break;
      case E_ERROR:
      case E_USER_ERROR:
        $error = 'Fatal Error';
        break;
      default:
        $error = 'Unknown';
        break;
    }
      
    if ($config->get('config_error_display')) {
      echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }
    
    if ($config->get('config_error_log')) {
      $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }

    return true;
  }

  // Error Handler
  set_error_handler('error_handler');

  // Request
  $request = new Request();
  $registry->set('request', $request);

  // Response
  $response = new Response();
  $response->addHeader('Content-Type: text/html; charset=utf-8');
  $registry->set('response', $response); 

  // Cache
  if (substr(VERSION, 0, 1) == '2') {
    $cache = new Cache('file');
  } else {
    $cache = new Cache();
  }
  $registry->set('cache', $cache); 

  // Session
  $session = new Session();
  $registry->set('session', $session); 

  // Language
  $languages = array();

  $query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`"); 

  foreach ($query->rows as $result) {
    $languages[$result['code']] = $result;
  }

  $config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);
  $config->set('config_language', $languages[$config->get('config_admin_language')]['code']);

  // Language	
  $language = new Language($languages[$config->get('config_admin_language')]['directory']);
  if (isset($languages[$config->get('config_admin_language')]['filename'])) {
  $language->load($languages[$config->get('config_admin_language')]['filename']);	
  } else {
  $language->load($languages[$config->get('config_admin_language')]['directory']);
  }
  $registry->set('language', $language);

  // Document
  $registry->set('document', new Document()); 		

  // Front Controller
  $controller = new Front($registry);

  // Router
  $action = new Action('module/universal_import/cron');

  // Dispatch
  $controller->dispatch($action, new Action('error/not_found'));

  // Output
  $response->output();
}