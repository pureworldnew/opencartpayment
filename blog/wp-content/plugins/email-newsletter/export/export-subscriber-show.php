<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/export/export-setting.js"></script>
<?php
if (!session_id())
{
    session_start();
}

$_SESSION['exportcsv'] = "YES"; 

$cnt_subscriber = 0;
$cnt_users = 0;
$cnt_comment_author = 0;
$cnt_subscriber = $wpdb->get_var("select count(DISTINCT eemail_email_sub) from " . WP_eemail_TABLE_SUB);
$cnt_users = $wpdb->get_var("select count(DISTINCT user_email) from ". $wpdb->prefix . "users");
$cnt_comment_author = $wpdb->get_var("SELECT count(DISTINCT comment_author_email) from ". $wpdb->prefix . "comments WHERE comment_author_email <> ''");
if(strtoupper($wpdb->get_var("show tables like '". WP_eemail_TABLE_SCF . "'")) == strtoupper(WP_eemail_TABLE_SCF))  
{
	$cnt_contact_form = $wpdb->get_var("select count(DISTINCT gCF_email) from " . WP_eemail_TABLE_SCF);
}
else
{
	$cnt_contact_form = "NA";
}
?>

<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
  <h2><?php echo WP_eemail_TITLE; ?></h2>
  <h3>Export email address in csv format</h3>
  <div class="tool-box">
  <form name="frm_emailnewsletter" method="post">
  <table width="100%" class="widefat" id="straymanage">
    <thead>
      <tr>
        <th width="3%" class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
        <th width="5%" scope="col">Sno</th>
        <th scope="col">Export option</th>
		<th width="10%" scope="col">Total email</th>
        <th width="10%" scope="col">Action</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th width="3%" class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
        <th width="5%" scope="col">Sno</th>
        <th scope="col">Export option</th>
		<th width="10%" scope="col">Total email</th>
        <th width="10%" scope="col">Action</th>
      </tr>
    </tfoot>
    <tbody>
      <tr>
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>1</td>
        <td>Subscriber email address</td>
		<td><?php echo $cnt_subscriber; ?></td>
        <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'view_subscriber')" href="javascript:void(0);">Click to export csv</a> </td>
      </tr>
      <tr class="alternate">
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>2</td>
        <td>Registered email address</td>
		<td><?php echo $cnt_users; ?></td>
        <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'registered_user')" href="javascript:void(0);">Click to export csv</a> </td>
      </tr>
      <tr>
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>3</td>
        <td>Comments author email address</td>
		<td><?php echo $cnt_comment_author; ?></td>
        <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'commentposed_user')" href="javascript:void(0);">Click to export csv</a> </td>
      </tr>
      <tr class="alternate">
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>4</td>
        <td>Contact form email address</td>
		<td><?php echo $cnt_contact_form; ?></td>
        <td>
		<?php if($cnt_contact_form <> 'NA') { ?>
		<a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'contact_user')" href="javascript:void(0);">Click to export csv</a> 
		<?php } else { echo $cnt_contact_form; } ?>
		</td>
      </tr>
    </tbody>
  </table>
  </form>
  <div class="tablenav">
	  <h2>
		<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Add Email</a> 
		<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Import Email</a> 
		<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>">Help</a>
	  </h2>
  </div>
  <div style="height:10px;"></div>
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
  </div>
</div>
