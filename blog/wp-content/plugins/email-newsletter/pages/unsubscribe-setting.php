<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/pages/pages-setting.js"></script>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php echo WP_eemail_TITLE; ?></h2>
    <h3>Unsubscribe link setting</h3>
	<?php
	$eemail_un_option = get_option('eemail_un_option');
	$eemail_un_text = get_option('eemail_un_text');
	$eemail_un_link = get_option('eemail_un_link');
	
	if (@$_POST['eemail_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('eemail_form_unsubscribe');
		
		$eemail_un_option = stripslashes($_POST['eemail_un_option']);
		$eemail_un_text = stripslashes($_POST['eemail_un_text']);
		$eemail_un_link = stripslashes($_POST['eemail_un_link']);
		
		update_option('eemail_un_option', $eemail_un_option );
		update_option('eemail_un_text', $eemail_un_text );
		update_option('eemail_un_link', $eemail_un_link );
		
		?>
		<div class="updated fade">
			<p><strong>Details successfully updated.</strong></p>
		</div>
		<?php
	}
	?>
	<form name="form_eemail" method="post" action="">
	
	<label for="tag-title">Unsubscribe Option</label>
	<select name="eemail_un_option" id="eemail_un_option">
		<option value="Yes" <?php if($eemail_un_option=='Yes') { echo 'selected' ; } ?>>Yes, Add an unsubscribe link in email newletter.</option>
		<option value="No" <?php if($eemail_un_option=='No') { echo 'selected' ; } ?>>No, Dont want unsubscribe link in email newletter.</option>
	</select>
	<p>Please enter your option from the list.</p>
	
	<label for="tag-title">Unsubscribe text</label>
	<textarea name="eemail_un_text" cols="80" rows="5"><?php echo $eemail_un_text; ?></textarea>
	<p>Please enter your unsubscribe text.</p>
	
	<label for="tag-title">Unsubscribe link</label>
	<input name="eemail_un_link" type="text" size="120" value="<?php echo $eemail_un_link; ?>" />
	<p>Please enter your unsubscribe link.</p>
	
	<p style="padding-top:10px;">
		<input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button add-new-h2" value="Update Settings" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="Cancel" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="Help" type="button" />
	</p>
	<?php wp_nonce_field('eemail_form_unsubscribe'); ?>
	</form>
	</div><br />
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>