<?php

require_once('../config.php');

// Install 
if (!defined('DIR_APPLICATION')) {
    header('Location: install/index.php');
    exit;
}

require_once(DIR_SYSTEM . 'startup.php');

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


//echo $_GET['action'];

if ($_GET['action'] == "Getres"){
	//echo "HHHH";
	$id = $_GET['olid'];
	$que = "select res_values from " . DB_PREFIX . "order_logs where order_log_id = '$id'";
	//echo $que;
	$query = $db->query($que);
	$result = $query->row;
	//return $result;
	$res = unserialize($result['res_values']);
	echo json_encode($res);
}
?> 
