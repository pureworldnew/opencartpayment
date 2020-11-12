<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/pages/pages-setting.js"></script>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php echo WP_eemail_TITLE; ?></h2>
    <h3>Email setting</h3>
	<?php
	$eemail_from_name = get_option('eemail_from_name');
	$eemail_from_email = get_option('eemail_from_email');
	
	$eemail_admin_email_option = get_option('eemail_admin_email_option');
	$eemail_admin_email_address = get_option('eemail_admin_email_address');
	$eemail_admin_email_content = get_option('eemail_admin_email_content');
	$eemail_user_email_option = get_option('eemail_user_email_option');
	$eemail_user_email_content = get_option('eemail_user_email_content');
	$eemail_email_type = get_option('eemail_email_type');
	
	$eemail_admin_email_subject = get_option('eemail_admin_email_subject');
	$eemail_user_email_subject = get_option('eemail_user_email_subject');
	
	if (@$_POST['eemail_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('eemail_form_email');
		
		$eemail_from_name = stripslashes($_POST['eemail_from_name']);
		$eemail_from_email = stripslashes($_POST['eemail_from_email']);
		
		$eemail_admin_email_option = stripslashes($_POST['eemail_admin_email_option']);
		$eemail_admin_email_address = stripslashes($_POST['eemail_admin_email_address']);
		$eemail_admin_email_content = stripslashes($_POST['eemail_admin_email_content']);
		$eemail_user_email_option = stripslashes($_POST['eemail_user_email_option']);
		$eemail_user_email_content = stripslashes($_POST['eemail_user_email_content']);
		$eemail_email_type = stripslashes($_POST['eemail_email_type']);
		
		$eemail_admin_email_subject = stripslashes($_POST['eemail_admin_email_subject']);
		$eemail_user_email_subject = stripslashes($_POST['eemail_user_email_subject']);
		
		update_option('eemail_from_name', $eemail_from_name );
		update_option('eemail_from_email', $eemail_from_email );
		
		update_option('eemail_admin_email_option', $eemail_admin_email_option );
		update_option('eemail_admin_email_address', $eemail_admin_email_address );
		update_option('eemail_admin_email_content', $eemail_admin_email_content );
		update_option('eemail_user_email_option', $eemail_user_email_option );
		update_option('eemail_user_email_content', $eemail_user_email_content );
		update_option('eemail_email_type', $eemail_email_type );
		
		update_option('eemail_admin_email_subject', $eemail_admin_email_subject );
		update_option('eemail_user_email_subject', $eemail_user_email_subject );
		
		?>
		<div class="updated fade">
			<p><strong>Details successfully updated.</strong></p>
		</div>
		<?php
	}
	?>
	<form name="eemail_form" method="post" action="" onsubmit="return _email_setting()" >
	<label for="tag-title">From email name</label>
	<input name="eemail_from_name" id="eemail_from_name" type="text" value="<?php echo $eemail_from_name; ?>" maxlength="150" size="50" />
	<p>Please enter your from email name.</p>
	
	<label for="tag-title">From email address</label>
	<input name="eemail_from_email" id="eemail_from_email" type="text" value="<?php echo $eemail_from_email; ?>" maxlength="150" size="50" />
	<p>Please enter your from email address.</p>
	
	<label for="tag-title">Send auto email to admin</label>
	<select name="eemail_admin_email_option" id="eemail_admin_email_option">
		<option value=''>Select</option>
		<option value='YES' <?php if($eemail_admin_email_option == 'YES') { echo 'selected' ; } ?>>Yes</option>
		<option value='NO' <?php if($eemail_admin_email_option == 'NO') { echo 'selected' ; } ?>>No</option>
	</select>
	<p>Send email to admin when new user subscribed.</p>
	
	<label for="tag-title">Admin email address</label>
	<input name="eemail_admin_email_address" id="eemail_admin_email_address" type="text" value="<?php echo $eemail_admin_email_address; ?>" maxlength="150" size="50" />
	<p>Please enter admin email address to received email.</p>
	
	<label for="tag-title">Admin email subject</label>
	<input name="eemail_admin_email_subject" id="eemail_admin_email_subject" type="text" value="<?php echo $eemail_admin_email_subject; ?>" maxlength="150" size="50" />
	<p>Please enter admin email subject.</p>
	
	<label for="tag-title">Admin email content</label>
	<textarea name="eemail_admin_email_content" id="eemail_admin_email_content" cols="100" rows="6"><?php echo esc_html(stripslashes($eemail_admin_email_content)); ?></textarea>
	<p>Please enter admin email content. (Keyword: ##USEREMAIL##)</p>
	
	<label for="tag-title">Send auto email to subscriber</label>
	<select name="eemail_user_email_option" id="eemail_user_email_option">
		<option value=''>Select</option>
		<option value='YES' <?php if($eemail_user_email_option == 'YES') { echo 'selected' ; } ?>>Yes</option>
		<option value='NO' <?php if($eemail_user_email_option == 'NO') { echo 'selected' ; } ?>>No</option>
	</select>
	<p>Send welcome email to subscriber.</p>
	
	<label for="tag-title">Subscriber email subject</label>
	<input name="eemail_user_email_subject" id="eemail_user_email_subject" type="text" value="<?php echo $eemail_user_email_subject; ?>" maxlength="150" size="50" />
	<p>Please enter Subscriber email subject.</p>
	
	<label for="tag-title">Subscriber email content</label>
	<textarea name="eemail_user_email_content" id="eemail_user_email_content" cols="100" rows="6"><?php echo esc_html(stripslashes($eemail_user_email_content)); ?></textarea>
	<p>Please enter subscriber welcome email content.</p>
	
	<label for="tag-title">Email type</label>
	<select name="eemail_email_type" id="eemail_email_type">
		<option value='HTML' <?php if($eemail_email_type == 'HTML') { echo 'selected' ; } ?>>HTML Email</option>
		<option value='PLAINTEXT' <?php if($eemail_email_type == 'PLAINTEXT') { echo 'selected' ; } ?>>Plain Text</option>
	</select>
	<p>Please enter subscriber welcome email content.</p>
	
	<p style="padding-top:10px;">
		<input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button add-new-h2" value="Update Settings" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="Cancel" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="Help" type="button" />
	</p>
	<?php wp_nonce_field('eemail_form_email'); ?>
	</form>
	</div><br />
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>