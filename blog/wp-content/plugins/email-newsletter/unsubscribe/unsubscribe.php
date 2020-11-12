<?php
$eemail_abspath = dirname(__FILE__);
$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/unsubscribe', '', $eemail_abspath);
$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\unsubscribe', '', $eemail_abspath_1);
require_once($eemail_abspath_1 .'wp-config.php');
$blogname = get_option('blogname');
?>
<html>
<head>
<title><?php echo $blogname; ?></title>
</head>
<body>
<?php
$form['rand'] = isset($_GET['rand']) ? $_GET['rand'] : '';
$form['user'] = isset($_GET['user']) ? $_GET['user'] : '';
$form['reff'] = isset($_GET['reff']) ? $_GET['reff'] : '';

if ($form['rand'] == '' || $form['user'] == '' || $form['reff'] == '')
{
	echo 'Oops.. Unexpected error occurred. Please try again.';
	die;
}
else
{
	global $wpdb;
	$result = '0';
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE_SUB."
		WHERE `eemail_id_sub` = %d and eemail_email_sub = '%s'",
		$form['rand'], $form['user']);
	$result = $wpdb->get_var($sSql);

	if ($result != '1')
	{
		echo 'Oops.. Your email address is not in our newsletter list. Please try again.';
	}
	else
	{
		$sSql = $wpdb->prepare("DELETE FROM `".WP_eemail_TABLE_SUB."` 
					WHERE `eemail_id_sub` = %d and eemail_email_sub = '%s' 
					LIMIT 1", $form['rand'], $form['user']);
			$wpdb->query($sSql);
			echo 'You have been successfully unsubscribed.';
	}
}
?>
</body>
</html>