<?php
$Email = "";
$Email = isset($_POST['txt_email_newsletter']) ? $_POST['txt_email_newsletter'] : '';
$Email = trim($Email);

if($Email <> "")
{
	$regex = '/^[A-z0-9][\w.+-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
	$eemail_valid_email = preg_match($regex, $Email);
	if($eemail_valid_email)
	{
		$eemail_abspath = dirname(__FILE__);
		$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/widget', '', $eemail_abspath);
		$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\widget', '', $eemail_abspath_1);
		require_once($eemail_abspath_1 .'wp-config.php');
		global $wpdb, $wp_version;
		global $user_login , $user_email;
		
		$result = '0';
		$sSql = $wpdb->prepare(
			"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE_SUB."
			WHERE `eemail_email_sub` = %s", $Email);
		$result = $wpdb->get_var($sSql);
		
		if ($result == '0')
		{
			$CurrentDate = date('Y-m-d G:i:s'); 
			$sql = $wpdb->prepare(
				"INSERT INTO `". WP_eemail_TABLE_SUB ."`
				(`eemail_name_sub`,`eemail_email_sub`, `eemail_status_sub`, `eemail_date_sub`)
				VALUES(%s, %s, %s, %s)",
				array('No Name', $Email, 'YES', $CurrentDate)
			);
			$wpdb->query($sql);
			
			$eemail_admin_email_option =  strtoupper(get_option('eemail_admin_email_option'));
			$eemail_user_email_option = strtoupper(get_option('eemail_user_email_option'));
			$eemail_admin_email_address = get_option('eemail_admin_email_address');
			$eemail_from_name = get_option('eemail_from_name');
			$eemail_from_email = get_option('eemail_from_email');
			
			if($eemail_admin_email_address == "")
			{
				get_currentuserinfo();
				$eemail_admin_email_address = $user_email;
			}
				
			if($eemail_from_name == "" || $eemail_from_email == "")
			{
				get_currentuserinfo();
				$eemail_from_name = $user_login;
				$eemail_from_email = $user_email;
			}
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= "From: \"$eemail_from_name\" <$eemail_from_email>\n";
			
			if(trim($eemail_admin_email_option) == "YES")
			{
				$to_email = $eemail_admin_email_address;
				$to_subject = get_option('eemail_admin_email_subject');
				$to_message = get_option('eemail_admin_email_content');
				$to_message = str_replace("\r\n", "<br />", $to_message);
				$to_message = str_replace("##USEREMAIL##", $Email, $to_message);
				@wp_mail($to_email, $to_subject, $to_message, $headers);
			}
			if(trim($eemail_user_email_option) == "YES")
			{
				$to_email = $Email;
				$to_subject = get_option('eemail_user_email_subject');
				$to_message = get_option('eemail_user_email_content');
				$to_message = str_replace("\r\n", "<br />", $to_message);
				@wp_mail($to_email, $to_subject, $to_message, $headers);
			}
			echo "subscribed-successfully";
		}
		else
		{
			echo "already-exist";
		}
	}
	else
	{
		echo "invalid-email";
	}
}
else
{
	echo "unexpected-error";
}
	
?>