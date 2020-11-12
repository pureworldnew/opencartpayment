<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE_SUB."
	WHERE `eemail_id_sub` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$eemail_errors = array();
	$eemail_success = '';
	$eemail_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_eemail_TABLE_SUB."`
		WHERE `eemail_id_sub` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'eemail_name_sub' => $data['eemail_name_sub'],
		'eemail_email_sub' => $data['eemail_email_sub'],
		'eemail_status_sub' => $data['eemail_status_sub'],
		'eemail_date_sub' => $data['eemail_date_sub']
	);
}
// Form submitted, check the data
if (isset($_POST['eemail_form_submit']) && $_POST['eemail_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_form_edit');
	
	$form['eemail_email_sub'] = isset($_POST['eemail_email_sub']) ? $_POST['eemail_email_sub'] : '';
	if ($form['eemail_email_sub'] == '')
	{
		$eemail_errors[] = __('Please enter email address.', WP_eemail_UNIQUE_NAME);
		$eemail_error_found = TRUE;
	}

	$form['eemail_name_sub'] = isset($_POST['eemail_name_sub']) ? $_POST['eemail_name_sub'] : '';
	$form['eemail_status_sub'] = isset($_POST['eemail_status_sub']) ? $_POST['eemail_status_sub'] : '';

	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_eemail_TABLE_SUB."`
				SET `eemail_email_sub` = %s,
				`eemail_name_sub` = %s,
				`eemail_status_sub` = %s
				WHERE eemail_id_sub = %d
				LIMIT 1",
				array($form['eemail_email_sub'], $form['eemail_name_sub'], $form['eemail_status_sub'], $did)
			);
		$wpdb->query($sSql);
		
		$eemail_success = 'Email was successfully updated.';
	}
}

if ($eemail_error_found == TRUE && isset($eemail_errors[0]) == TRUE)
{
?>
  <div class="error fade">
    <p><strong><?php echo $eemail_errors[0]; ?></strong></p>
  </div>
  <?php
}
if ($eemail_error_found == FALSE && strlen($eemail_success) > 0)
{
?>
  <div class="updated fade">
    <p><strong><?php echo $eemail_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber">Click here</a> to view the details</strong></p>
  </div>
  <?php
}
?>
<script language="javaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/subscriber/subscriber-setting.js"></script>
<div class="form-wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php echo WP_eemail_TITLE; ?></h2>
	<form name="eemail_form" method="post" action="#" onsubmit="return _eemail_submit()"  >
      <h3>Edit email</h3>
	  <label for="tag-image">Enter email address.</label>
      <input name="eemail_email_sub" type="text" id="eemail_email_sub" value="<?php echo esc_html(stripslashes($form['eemail_email_sub'])); ?>" size="50" />
      <p>Please enter email address.</p>
	  <label for="tag-image">Enter name.</label>
      <input name="eemail_name_sub" type="text" id="eemail_name_sub" value="<?php echo esc_html(stripslashes($form['eemail_name_sub'])); ?>" size="50" />
      <p>Please enter email name.</p>
      <label for="tag-display-status">Display status</label>
      <select name="eemail_status_sub" id="eemail_status_sub">
        <option value=''>Select</option>
		<option value='YES' <?php if(strtoupper($form['eemail_status_sub'])=='YES') { echo 'selected="selected"' ; } ?>>Yes</option>
        <option value='NO' <?php if(strtoupper($form['eemail_status_sub'])=='NO') { echo 'selected="selected"' ; } ?>>No</option>
      </select>
      <p>Do you want to show this email in Send Mail admin pages?.</p>
      <input name="eemail_id_sub" id="eemail_id_sub" type="hidden" value="">
      <input type="hidden" name="eemail_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Update Details" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="eemail_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('eemail_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>