<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE."
	WHERE `eemail_id` = %d",
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
		FROM `".WP_eemail_TABLE."`
		WHERE `eemail_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'eemail_subject' => $data['eemail_subject'],
		'eemail_content' => $data['eemail_content'],
		'eemail_status' => $data['eemail_status'],
		'eemail_date' => $data['eemail_date']
	);
}
// Form submitted, check the data
if (isset($_POST['eemail_form_submit']) && $_POST['eemail_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_form_edit');
	
	$form['eemail_subject'] = isset($_POST['eemail_subject']) ? $_POST['eemail_subject'] : '';
	if ($form['eemail_subject'] == '')
	{
		$eemail_errors[] = __('Please enter email subject.', WP_eemail_UNIQUE_NAME);
		$eemail_error_found = TRUE;
	}

	$form['eemail_content'] = isset($_POST['eemail_content']) ? $_POST['eemail_content'] : '';
	$form['eemail_status'] = isset($_POST['eemail_status']) ? $_POST['eemail_status'] : '';

	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_eemail_TABLE."`
				SET `eemail_subject` = %s,
				`eemail_content` = %s,
				`eemail_status` = %s
				WHERE eemail_id = %d
				LIMIT 1",
				array($form['eemail_subject'], $form['eemail_content'], $form['eemail_status'], $did)
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
    <p><strong><?php echo $eemail_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email">Click here</a> to view the details</strong></p>
  </div>
  <?php
}
?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/compose/compose-email-setting.js"></script>
<div class="form-wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php echo WP_eemail_TITLE; ?></h2>
	<form name="eemail_form" method="post" action="#" onsubmit="return _eemail_submit()"  >
      <h3>Edit email</h3>
	  <label for="tag-image">Enter email subject.</label>
      <input name="eemail_subject" type="text" id="eemail_subject" value="<?php echo esc_html(stripslashes($form['eemail_subject'])); ?>" size="90" />
      <p>Please enter your email subject.</p>
	  <label for="tag-link">Enter email content</label>
      <textarea name="eemail_content" cols="140" rows="25" id="eemail_content"><?php echo esc_html(stripslashes($form['eemail_content'])); ?></textarea>
      <p>This page is where you write, save your email messages. We can add HTML content.</p>
      <label for="tag-display-status">Display status</label>
      <select name="eemail_status" id="eemail_status">
        <option value=''>Select</option>
		<option value='YES' <?php if($form['eemail_status']=='YES') { echo 'selected="selected"' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['eemail_status']=='NO') { echo 'selected="selected"' ; } ?>>No</option>
      </select>
      <p>Do you want to show this email in Send Mail admin pages?.</p>
      <input name="eemail_id" id="eemail_id" type="hidden" value="">
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