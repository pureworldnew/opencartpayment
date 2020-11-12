<div class="wrap">
<?php
$eemail_errors = array();
$eemail_success = '';
$eemail_error_found = FALSE;

// Preset the form fields
$form = array(
	'eemail_email_sub' => ''
);

// Form submitted, check the data
if (isset($_POST['eemail_form_submit']) && $_POST['eemail_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_form_importemails');
	
	$form['importemails'] = isset($_POST['importemails']) ? $_POST['importemails'] : '';
	if ($form['importemails'] == '')
	{
		$eemail_errors[] = __('Please enter email address.', WP_eemail_UNIQUE_NAME);
		$eemail_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{
		$ArrayEmail = explode(',', $form['importemails']);
		$Inserted = 0;
		$Duplicate = 0;
		$CurrentDate = date('Y-m-d G:i:s'); 
		for ($i = 0; $i < count($ArrayEmail); $i++)
		{
			$cSql = "select * from ".WP_eemail_TABLE_SUB." where eemail_email_sub='" . trim($ArrayEmail[$i]). "'";
			$data = $wpdb->get_results($cSql);
			if ( empty($data) ) 
			{
				$sql = $wpdb->prepare(
					"INSERT INTO `".WP_eemail_TABLE_SUB."`
					(`eemail_name_sub`,`eemail_email_sub`, `eemail_status_sub`, `eemail_date_sub`)
					VALUES(%s, %s, %s, %s)",
					array('No Name', $ArrayEmail[$i], 'YES', $CurrentDate)
				);
				$wpdb->query($sql);
				$Inserted = $Inserted + 1;
			}
			else
			{
				$Duplicate = $Duplicate + 1;
			}
		}
		$eemail_success[] = __($Inserted . ' Email(s) was successfully imported.', WP_eemail_UNIQUE_NAME);
		$eemail_success[] = __($Duplicate . ' Email(s) are already in our database.', WP_eemail_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'eemail_email_sub' => ''
		);
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
if ($eemail_error_found == FALSE && isset($eemail_success[0]) == TRUE)
{
	?>
	  <div class="updated fade">
		<p>
		<strong>
		<?php echo $eemail_success[0]; ?> <br />
		<?php echo $eemail_success[1]; ?> <br />
		<a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber">Click here</a> to view the details</strong>
		</p>
	  </div>
	  <?php
	}
?>
<script language="javaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/subscriber/subscriber-setting.js"></script>
<div class="form-wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php echo WP_eemail_TITLE; ?></h2>
	<form name="form_importemails" method="post" action="#" onsubmit="return _eemail_import()"  >
      <h3>Add email/Import email</h3>
      <label for="tag-image">Enter email subject.</label>
      <textarea name="importemails" cols="120" rows="8"></textarea>
      <p>Enter the email address with comma separated (No comma at the end).</p>
	  <input name="eemail_id" id="eemail_id" type="hidden" value="">
      <input type="hidden" name="eemail_form_submit" value="yes"/>
	  <div style="padding-top:5px;"></div>
      <p>
        <input name="publish" lang="publish" class="button add-new-h2" value="Insert Details" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('eemail_form_importemails'); ?>
    </form>
</div>
<h3>Note</h3>
<ol>
	<li>Enter your email address with comma separated.</li>
	<li>Enter maximum 25 email address at one time.</li>
	<li>Comma not allowed at the end of the string.</li>
</ol>
<h3>Wrong format</h3>
<ol>
	<li>admin@gmail.com,admin1@gmail.com, &nbsp;&nbsp;&nbsp;&nbsp;(Comma at the end.)</li>
	<li>admin@gmail.com,,admin1@gmail.com &nbsp;&nbsp;&nbsp;&nbsp;(Two comma.)</li>
</ol>
<h3>Correct format</h3>
<ol>
	<li>admin@gmail.com,admin1@gmail.com</li>
</ol>
<p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>