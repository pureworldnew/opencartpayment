<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache');

if (file_exists('../../config.php')) {
	require_once('../../config.php');
}
require_once(DIR_SYSTEM . 'startup.php');

require_once(DIR_SYSTEM . 'library/db.php');

// user 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$json = array('error' => 1);
if (isset($_POST['username']) && isset($_POST['password'])) {
	$user_query = $db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $db->escape($_POST['username']) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $db->escape($_POST['password']) . "'))))) OR password = '" . $db->escape(md5($_POST['password'])) . "') AND status = '1'");
	if ($user_query->num_rows) {
		$json = array('success' => 1);
	}
}
echo json_encode($json);

?>