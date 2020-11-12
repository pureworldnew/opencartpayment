<?php
// Form submitted, check the data
if (isset($_POST['frm_eemail_display']) && $_POST['frm_eemail_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$eemail_success = '';
	$eemail_success_msg = FALSE;
	
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
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('eemail_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_eemail_TABLE."`
					WHERE `eemail_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$eemail_success_msg = TRUE;
			$eemail_success = __('Selected record was successfully deleted.', WP_eemail_UNIQUE_NAME);
		}
	}
	
	if ($eemail_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $eemail_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
    <h2><?php echo WP_eemail_TITLE; ?></h2>
	<h3>Compose email  <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email&amp;ac=add">Add New</a></h3>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_eemail_TABLE."` order by eemail_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/compose/compose-email-setting.js"></script>
		<form name="frm_eemail_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="3%" class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
			<th scope="col">Email subject</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
			<th scope="col">Email subject</th>
            <th scope="col">Status</th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			$displayisthere = FALSE;
			if(count($myData) > 0)
			{
				$i = 1;
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['eemail_id']; ?>" name="eemail_group_item[]"></td>
					  <td><?php echo esc_html(stripslashes($data['eemail_subject'])); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email&amp;ac=edit&amp;did=<?php echo $data['eemail_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:_eemail_delete('<?php echo $data['eemail_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
						</div>
					  </td>
						<td><?php echo $data['eemail_status']; ?></td>
					</tr>
					<?php
					$i = $i+1;
				}
			}
			else
			{
				?><tr><td colspan="3" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('eemail_form_show'); ?>
		<input type="hidden" name="frm_eemail_display" value="yes"/>
      </form>	
	  <div class="tablenav">
		  <h2>
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email&amp;ac=add">Compose New Email</a>
			<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>">Help</a>
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Add Email</a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Import Email</a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=export-subscriber">Export Email (CSV)</a> 
		  </h2>
	  </div>
	  <div style="height:10px;"></div>
	  <p class="description"><?php echo WP_eemail_LINK; ?></p>
	</div>
</div>