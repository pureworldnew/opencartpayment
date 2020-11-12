<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

if (file_exists('../../config.php')) {
	require_once('../../config.php');
}
require_once(DIR_SYSTEM . 'startup.php');
require_once(DIR_SYSTEM . 'library/db.php');

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

function send_order_info($id, $order) {
	echo "id: $id" . PHP_EOL;
	echo "data: $order" . PHP_EOL;
	echo PHP_EOL;
	ob_flush();
	flush();
}

// use the server time when the connection was established as the id
$connectedAt = time();
set_time_limit( 200 );
	
$max_order_id = 0;
do {
	if ((time() - $connectedAt) > 25) {
		// only allow connection kept alive for 25 seconds
		die();
	}
	
	// Get the online orders
	$order_query = $db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_id > 0 AND user_id IS NULL AND order_status_id > 0");
	send_order_info($connectedAt, $order_query->num_rows);
	
	// sleep for 10 seconds
	sleep(10);
} while (true);

?>