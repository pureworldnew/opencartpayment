<?php

/**
 * Contains part of the Opencart Authorize.Net CIM Payment Module code.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to memiiso license.
 * Please see the LICENSE.txt file for more information.
 * All other rights reserved.
 *
 * @author     memiiso <gel.yine.gel@hotmail.com>
 * @copyright  2013-~ memiiso
 * @license    Commercial License. Please see the LICENSE.txt file
 */

// include authorize netlibraries
require_once dirname(__FILE__) . '/autoload.php';
require_once dirname(__FILE__) . '/cimController.php';
require_once dirname(__FILE__) . '/CreditCardType.php';

global $registry;
$config = $registry->get('config');

if($config->get('authorizenet_cim_server')=='live'){
	define('AUTHORIZENET_API_LOGIN_ID', $config->get('authorizenet_cim_live_login'));
	define('AUTHORIZENET_TRANSACTION_KEY',$config->get('authorizenet_cim_live_key'));
	define('AUTHORIZENET_SANDBOX', false);
} else {
	define('AUTHORIZENET_API_LOGIN_ID', $config->get('authorizenet_cim_sandbox_login'));
	define('AUTHORIZENET_TRANSACTION_KEY',$config->get('authorizenet_cim_sandbox_key'));
	define('AUTHORIZENET_SANDBOX', true);
}


$cimvalidation_mode = $config->get('authorizenet_cim_validation_mode');
if (!in_array($cimvalidation_mode, array("testMode","liveMode"))  ) {
	define('AUTHORIZENET_VALIDATIONMODE', 'none');
}else {
	define('AUTHORIZENET_VALIDATIONMODE', $cimvalidation_mode); 
}

$md5=$config->get('authorizenet_cim_hash');
if ($md5) {
	define('AUTHORIZENET_MD5_SETTING',$md5);
}else {
	//define('AUTHORIZENET_MD5_SETTING','');
}

// init log file
$authorizenetcimlogf='authorizenet_cim/error.log';
if($config->get('authorizenet_cim_daily_log')=='daily'){
	$authorizenetcimlogf='authorizenet_cim/'.date("Y-m-d").'-error.log';
}
$authorizenetcimlog = new Log($authorizenetcimlogf);
$registry->set('authorizenet_cim_log', $authorizenetcimlog);

// init debug log 
if($config->get('authorizenet_cim_debug_log')=='create'){
	if($config->get('authorizenet_cim_daily_log')=='daily'){
		define('AUTHORIZENET_LOG_FILE', DIR_LOGS.'authorizenet_cim/'.date("Y-m-d").'-debug.log');
	}else {
	define('AUTHORIZENET_LOG_FILE', DIR_LOGS.'authorizenet_cim/debug.log');
	}
}


// take some options from opencart 1.5.5
if (!function_exists('utf8_strlen')) {
  function utf8_strlen($string) {
  return strlen(utf8_decode($string));
  }
}
if (!class_exists('Url')) {
	require_once dirname(__FILE__) . '/url.php';
}

?>