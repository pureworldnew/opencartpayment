<?php
@ini_set("max_execution_time","0");
//date_default_timezone_set('America/New_York');
require(__DIR__."/config.php");
require_once(__DIR__."/system/library/db.php");
require_once(__DIR__."/system/library/db/mysqli.php");

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

//Functions
function getCompletedOrderStatusIds($db) {
	$key = "config_complete_status";
	$query = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` LIKE '" . $key . "'");
	$result = $query->row['value'];
	$result = str_replace("[","", $result);
	$result = str_replace("]","", $result);
	return (string)$result;
}

function getProducts($db)
{
	$today = date("Y-m-d");
	$query = $db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE DATE(date_modified) <> '". $today ."'");
	return $query->rows;
}

$products = getProducts($db);

foreach($products as $product)
{
	$product_id = $product['product_id'];
	$query = $db->query("SELECT op.`product_id`, sum(op.quantity_supplied) as sold FROM ".DB_PREFIX."order_product op INNER JOIN ".DB_PREFIX."order o ON op.order_id = o.order_id WHERE op.`product_id` ='".$product_id."' AND o.order_status_id IN(" . getCompletedOrderStatusIds($db) . ")");
	$sold = $query->row ? $query->row['sold'] : 0;
	$db->query("UPDATE `".DB_PREFIX."product` SET `sold` = '".$sold."', date_modified = NOW() WHERE product_id='".$product_id."'");
	
}
echo 'Done';