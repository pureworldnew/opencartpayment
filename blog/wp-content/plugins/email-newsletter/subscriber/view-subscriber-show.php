<?php
// Form submitted, check the data
$search = isset($_GET['search']) ? $_GET['search'] : 'A,B,C';
if (isset($_POST['frm_eemail_display']) && $_POST['frm_eemail_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$eemail_success = '';
	$eemail_success_msg = FALSE;
	
	if (isset($_POST['frm_eemail_bulkaction']) && $_POST['frm_eemail_bulkaction'] != 'delete')
	{
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
			?>
			<div class="error fade">
			  <p><strong>Oops, selected details doesn't exist (1).</strong></p>
			</div>
			<?php
		}
		else
		{
			// Form submitted, check the action
			if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
			{
				//	Just security thingy that wordpress offers us
				check_admin_referer('eemail_form_show');
				
				//	Delete selected record from the table
				$sSql = $wpdb->prepare("DELETE FROM `".WP_eemail_TABLE_SUB."`
						WHERE `eemail_id_sub` = %d
						LIMIT 1", $did);
				$wpdb->query($sSql);
				
				//	Set success message
				$eemail_success_msg = TRUE;
				$eemail_success = __('Selected record was successfully deleted.', WP_eemail_UNIQUE_NAME);
			}
		}
	}
	else
	{
		check_admin_referer('eemail_form_show');
		
		$chk_delete = $_POST['chk_delete'];
		if(!empty($chk_delete))
		{			
			$count = count($chk_delete);
			for($i=0; $i<$count; $i++)
			{
				$del_id = $chk_delete[$i];
				$sql = "delete FROM ".WP_eemail_TABLE_SUB." WHERE eemail_id_sub=".$del_id." Limit 1";
				$wpdb->get_results($sql);
			}
			
			//	Set success message
			$eemail_success_msg = TRUE;
			$eemail_success = __('Selected ('.$count.') record was successfully deleted.', WP_eemail_UNIQUE_NAME);
		}
		else
		{
			?>
			<div class="error fade">
			  <p><strong>Oops, No record was selected.</strong></p>
			</div>
			<?php
		}
	}
	
	if ($eemail_success_msg == TRUE)
	{
		?>
		<div class="updated fade">
		  <p><strong><?php echo $eemail_success; ?></strong></p>
		</div>
		<?php
	}
}
?>
<script language="javaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/subscriber/subscriber-setting.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
  <h2><?php echo WP_eemail_TITLE; ?></h2>
  <h3>View subscriber <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Add New</a></h3>
  <div class="tool-box">
    <?php
		$sSql = "SELECT * FROM `".WP_eemail_TABLE_SUB."` where 1=1";
		if($search <> "")
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
		$sSql = $sSql . " ORDER BY eemail_email_sub";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
	<div class="tablenav">
		<span style="text-align:left;">
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=A,B,C">A,B,C</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=D,E,F">D,E,F</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=G,H,I">G,H,I</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=J,K,L">J,K,L</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=M,N,O">M,N,O</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=P,Q,R">P,Q,R</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=S,T,U">S,T,U</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=V,W,X,Y,Z">V,W,X,Y,Z</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=0,1,2,3,4,5,6,7,8,9">0-9</a> 
		<span>
		<span style="float:right;">
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Add Email</a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Import Email</a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=export-subscriber">Export Email (CSV)</a> 
			<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>">Help</a> 
		</span>
    </div>
    <form name="frm_eemail_display" method="post" onsubmit="return _subscribermultipledelete()">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="chk_delete[]" id="chk_delete[]" /></th>
            <th scope="col" width="5%">Sno</th>
            <th scope="col">Email address</th>
            <th scope="col" width="10%">Action</th>
			<th scope="col" width="10%">Display</th>
            <th scope="col" width="10%">Email db id</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="chk_delete[]" id="chk_delete[]" /></th>
            <th scope="col" width="5%">Sno</th>
            <th scope="col">Email address</th>
            <th scope="col" width="10%">Action</th>
			<th scope="col" width="10%">Display</th>
            <th scope="col" width="10%">Email db id</th>
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
            <td align="left"><input name="chk_delete[]" id="chk_delete[]" type="checkbox" value="<?php echo $data['eemail_id_sub'] ?>" /></td>
            <td><?php echo $i; ?></td>
            <td><?php echo $data['eemail_email_sub']; ?></td>
            <td><div> <span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=edit&amp;did=<?php echo $data['eemail_id_sub']; ?>">Edit</a> | </span> <span class="trash"><a onClick="javascript:_eemail_delete('<?php echo $data['eemail_id_sub']; ?>')" href="javascript:void(0);">Delete</a></span> </div></td>
            <td><?php echo $data['eemail_status_sub']; ?></td>
			<td><?php echo $data['eemail_id_sub']; ?></td>
          </tr>
          <?php
					$i = $i+1;
				} 
			}
			else
			{
				?>
          <tr>
            <td colspan="6" align="center">No records available.</td>
          </tr>
          <?php 
			}
			?>
        </tbody>
      </table>
      <?php wp_nonce_field('eemail_form_show'); ?>
      <input type="hidden" name="frm_eemail_display" value="yes"/>
	  <input type="hidden" name="frm_eemail_bulkaction" value=""/>
	  <input name="searchquery" id="searchquery" type="hidden" value="<?php echo $search; ?>" />
	<div style="padding-top:10px;"></div>
    <div class="tablenav">
		<div class="alignleft">
			<select name="action" id="action">
				<option value="">Bulk Actions</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" value="Apply" class="button action" id="doaction" name="">
		</div>
		<div class="alignright">
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Add Email</a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add">Import Email</a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=export-subscriber">Export Email (CSV)</a> 
			<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>">Help</a> 
		</div>
    </div>
	</form>
    <br />
    <p class="description"><?php echo WP_eemail_LINK; ?></p>
  </div>
</div>
