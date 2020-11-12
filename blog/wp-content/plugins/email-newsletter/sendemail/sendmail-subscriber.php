<?php
$eemail_errors = array();
$eemail_success = '';
$eemail_error_found = FALSE;

$search = isset($_GET['search']) ? $_GET['search'] : 'A,B,C';
if (isset($_POST['eemail_sendmail_subscriber']) && $_POST['eemail_sendmail_subscriber'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_sendmail_subscriber');

	$form['eemail_subject_drop'] = isset($_POST['eemail_subject_drop']) ? $_POST['eemail_subject_drop'] : '';
	if ($form['eemail_subject_drop'] == '')
	{
		$eemail_errors[] = __('Please select email subject.', WP_eemail_UNIQUE_NAME);
		$eemail_error_found = TRUE;
	}
	
	$form['eemail_checked'] = isset($_POST['eemail_checked']) ? $_POST['eemail_checked'] : '';
	if ($form['eemail_checked'] == '')
	{
		$eemail_errors[] = __('Please select email address.', WP_eemail_UNIQUE_NAME);
		$eemail_error_found = TRUE;
	}
	$recipients = $_POST['eemail_checked'];
	
	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{
		$sSql = $wpdb->prepare(
				"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE."
				WHERE `eemail_id` = %d",
				array($form['eemail_subject_drop'])
			);
			$result = '0';
			$result = $wpdb->get_var($sSql);
			
			if ($result != '1')
			{
				?><div class="error fade"><p><strong>Oops, selected details doesn't exist</strong></p></div><?php
			}
			else
			{
				$num_sent = 0;
				$num_sent = eemail_send_mail($form['eemail_checked'], $form['eemail_subject_drop'], "subscriber" );
				?>
				<div class="updated fade">
				<strong><p>Email has been sent to <?php echo $num_sent; ?> user(s), and <?php echo count($recipients);?> recipient(s) were originally found.</p></strong>
				</div>
				<?php
			}
	}
}
if ($eemail_error_found == TRUE && isset($eemail_errors[0]) == TRUE)
{
	?><div class="error fade"><p><strong><?php echo $eemail_errors[0]; ?></strong></p></div><?php
}
?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/sendemail/sendmail-setting.js"></script>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php echo WP_eemail_TITLE; ?> (Send email to subscribed users)</h2>
	<h3>Select email address from subscribed users list:</h3>
	<div style="padding-bottom:14px;padding-top:5px;">
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=A,B,C">A, B, C</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=D,E,F">D, E, F</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=G,H,I">G, H, I</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=J,K,L">J, K, L</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=M,N,O">M, N, O</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=P,Q,R">P, Q, R</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=S,T,U">S, T, U</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=V,W,X,Y,Z">V, W, X, Y, Z</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=0,1,2,3,4,5,6,7,8,9">0 - 9</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=ALL">ALL</a>
	</div>
	<form name="form_eemail" method="post" action="#" onsubmit="return _send_email_submit()"  >
	<?php
	$sSql = "select distinct eemail_email_sub, eemail_id_sub from ".WP_eemail_TABLE_SUB." where 1=1"; 
	if($search <> "")
	{
		if($search <> "ALL")
		{
			$array = explode(',', $search);
			$length = count($array);
			for ($i = 0; $i < $length; $i++) 
			{
				if(@$i == 0)
				{
					$sSql = $sSql . " and";
				}
				else
				{
					$sSql = $sSql . " or";
				}
				$sSql = $sSql . " eemail_email_sub LIKE '" . $array[$i]. "%'";
			}
		}
	}
	$sSql = $sSql . " ORDER BY eemail_email_sub";
	$data = $wpdb->get_results($sSql);
	$count = 0;
	if ( !empty($data) ) 
	{
		echo "<table border='0' cellspacing='0'><tr>";
		$col=3;
		foreach ( $data as $data )
		{
			$to = $data->eemail_email_sub;
			$eemail_id_sub = $data->eemail_id_sub;
			$ToAddress = trim($to) . '<||>' . trim($eemail_id_sub);
			if($to <> "")
			{
				echo "<td style='padding-top:4px;padding-bottom:4px;padding-right:10px;'>";
				?>
				<input class="radio" type="checkbox" checked="checked" value='<?php echo $ToAddress; ?>' id="eemail_checked[]" name="eemail_checked[]">
				&nbsp;<?php echo $to; ?>
				<?php
				if($col > 1) 
				{
					$col=$col-1;
					echo "</td><td>"; 
				}
				elseif($col = 1)
				{
					$col=$col-1;
					echo "</td></tr><tr>";;
					$col=3;
				}
				$count = $count + 1;
			}
		}
		echo "</tr></table>";
	}
	else
	{
		$searchdisplay = "";
		if($search == "0,1,2,3,4,5,6,7,8,9")
		{
			$searchdisplay = "0 - 9";
		}
		else
		{
			$searchdisplay = $search;
		}
		echo "No email address available for this search result <strong>(".$searchdisplay.")</strong>. Please click above buttons to search.";
	}
	?>
	<div style="padding-top:14px;">
		Total emails: <?php echo $count; ?>
	</div>
	<div style="padding-top:14px;">
		<input class="button add-new-h2" type="hidden" name="send" value="true" />
		<input class="button add-new-h2" type="button" name="CheckAll" value="Check All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', true);">
		<input class="button add-new-h2" type="button" name="UnCheckAll" value="Uncheck All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', false);">
	</div>
	<?php
	$data = $wpdb->get_results("select eemail_id, eemail_subject  from ".WP_eemail_TABLE." where 1=1 and eemail_status='YES' order by eemail_id desc");
	if ( !empty($data) ) 
	{
		foreach ( $data as $data )
		{
			if($data->eemail_subject <> "")
			{
				@$eemail_subject_drop_val = @$eemail_subject_drop_val . '<option value="'.$data->eemail_id.'">' . stripcslashes($data->eemail_subject) . '</option>';
			}
		}
	}
	?>
	<h3>Select email subject</h3>
	<div>
		<select name="eemail_subject_drop" id="eemail_subject_drop">
			<option value=""> == Select Email Subject == </option>
			<?php echo $eemail_subject_drop_val; ?>
		</select>
	</div>
	<div style="padding-top:20px;">
	<input type="submit" name="Submit" class="button add-new-h2" value="Send Email" style="width:160px;" />&nbsp;&nbsp;
	<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="Cancel" type="button" />&nbsp;&nbsp;
    <input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="Help" type="button" />
	</div>
	<?php wp_nonce_field('eemail_sendmail_subscriber'); ?>
	<input type="hidden" name="eemail_sendmail_subscriber" id="eemail_sendmail_subscriber" value="yes"/>
	</form>
	</div>
	<?php include_once("steps.php"); ?>
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>