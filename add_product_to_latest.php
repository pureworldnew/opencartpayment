<?php
@ini_set("max_execution_time","0");
//date_default_timezone_set('America/New_York');
require(__DIR__."/config.php");
require_once(__DIR__."/system/library/db.php");
require_once(__DIR__."/system/library/db/mysqli.php");

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

//Functions

function getLatestCategory($db)
{
	$query = $db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name = 'latest'");
	return !empty($query->row) ? $query->row['category_id'] : 0;
}

function deleteOldProductsFromCat($db, $category_id)
{
	$db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
}

function getLatestProducts($db, $limit)
{
	$query = $db->query("SELECT product_id, date_added FROM " . DB_PREFIX . "product WHERE status = '1' AND date_added <= NOW() ORDER BY date_added DESC LIMIT " . (int)$limit . "");
	
	//echo "SELECT product_id, date_added FROM " . DB_PREFIX . "product WHERE status = '1' AND date_added <= NOW() ORDER BY date_added DESC LIMIT " . (int)$limit . "";
	
	return $query->rows;
}

function addProductToLatest($db, $product_id, $category_id)
{
	$db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
//	echo "INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'";
}

$limit = 100;

$category_id = getLatestCategory($db);

deleteOldProductsFromCat($db, $category_id);

$products = getLatestProducts($db, $limit);

foreach($products as $product)
{
	$product_id = $product['product_id'];
	addProductToLatest($db, $product_id, $category_id);
}

echo 'Latest Category products updated Successfully';