<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
<div id="emailtemplate">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $action_insert_template; ?>" class="btn btn-success" data-toggle="tooltip" title="<?php echo $text_create_template; ?>"><i class="fa fa-plus"></i></a>

				<a href="<?php echo $config_url; ?>" class="btn btn-info" data-toggle="tooltip" title="<?php echo $heading_config; ?>"><i class="fa fa-cogs"></i></a>

				<div class="btn-group" data-toggle="tooltip" title="<?php echo $button_tools; ?>">
				  <button type="button" data-toggle="dropdown" class="btn btn-warning dropdown-toggle"><i class="fa fa-wrench"></i></button>
				  <ul class="dropdown-menu pull-right" role="menu">
					<li><a href="<?php echo $logs_url; ?>"><i class="fa fa-inbox"></i> <?php echo $heading_logs; ?></a></li>
					<li><a href="<?php echo $test_url; ?>"><i class="fa fa-code"></i> <?php echo $heading_modification; ?></a></li>
				  </ul>
				</div>

				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>

			<h1><?php echo $heading_title; ?> <small style="vertical-align:middle"><?php echo $version; ?></small></h1>

			<ul class="breadcrumb">
        		<?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?>
        		<?php if ($i == count($breadcrumbs)) { ?>
        			<li class="active"><?php echo $breadcrumb['text']; ?></li>
        		<?php } else { ?>
        			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        		<?php } ?>
        		<?php $i++; } ?>
      		</ul>
		</div>
	</div>

	<form action="<?php echo $action; ?>" method="post" id="form-emailtemplate" class="container-fluid">
	    <?php if (!empty($error_warning)) { ?>
	    	<div class="alert alert-danger">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if (!empty($error_attention)) { ?>
	    	<div class="alert alert-warning">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_attention; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <?php if (!empty($success)) { ?>
	    	<div class="alert alert-success">
				<i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
	      		<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
	    <?php } ?>

	    <div class="filter-templates form-inline">
	    	<div class="form-group">
	    		<div class="radio">
	    			<label<?php if($emailtemplate_type == '') echo ' class="active"'; ?>>
	    				<input type="radio" name="filter_type" value=""<?php if($emailtemplate_type == '') echo ' checked="checked"'; ?> />
	    				<?php echo $text_all; ?>
	    			</label>
	    			<label<?php if($emailtemplate_type == 'order') echo ' class="active"'; ?>>
	    				<input type="radio" name="filter_type" value="order"<?php if($emailtemplate_type == 'order') echo ' checked="checked"'; ?> />
	    				<?php echo $text_order; ?>
	    			</label>
	    			<label<?php if($emailtemplate_type == 'customer') echo ' class="active"'; ?>>
	    				<input type="radio" name="filter_type" value="customer"<?php if($emailtemplate_type == 'customer') echo ' checked="checked"'; ?> />
	    				<?php echo $text_customer; ?>
	    			</label>
	    			<label<?php if($emailtemplate_type == 'affiliate') echo ' class="active"'; ?>>
	    				<input type="radio" name="filter_type" value="affiliate"<?php if($emailtemplate_type == 'affiliate') echo ' checked="checked"'; ?> />
    					<?php echo $text_affiliate; ?>
    				</label>
	    			<label<?php if($emailtemplate_type == 'admin') echo ' class="active"'; ?>>
	    				<input type="radio" name="filter_type" value="admin"<?php if($emailtemplate_type == 'admin') echo ' checked="checked"'; ?> />
	    				<?php echo $text_admin; ?>
	    			</label>
	    		</div>
	    	</div>

	    </div>

	    <div class="table-responsive">
			<table class="table table-bordered table-striped table-row-check" id="template_list">
				<thead>
					<tr>
						<th class="text-center" width="1">&nbsp;</th>
						<th><a href="<?php echo $sort_label; ?>" class="<?php if ($sort == 'label') echo strtolower($order); ?>"><?php echo $column_label; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_key; ?>" class="<?php if ($sort == 'key') echo strtolower($order); ?>"><?php echo $column_key; ?></a></th>
						<th class="text-center"><a href="<?php echo $sort_config; ?>" class="<?php if ($sort == 'config') echo strtolower($order); ?>"><?php echo $column_config; ?></a></th>
						<th class="text-center hidden-xs"><a href="<?php echo $sort_modified; ?>" class="<?php if ($sort == 'modified') echo strtolower($order); ?>"><?php echo $column_modified; ?></a></th>
	              		<th class="text-center"><a href="<?php echo $sort_shortcodes; ?>" class="<?php if ($sort == 'shortcodes') echo strtolower($order); ?>"><?php echo $column_shortcodes; ?></a></th>
	              		<th class="text-center"><a href="<?php echo $sort_status; ?>" class="<?php if ($sort == 'status') echo strtolower($order); ?>"><?php echo $column_status; ?></a></th>
						<th width="1" class="text-center"><input type="checkbox" data-checkall="input[name^='selected']" /></th>
		           	</tr>
	        	</thead>
	        	<?php if ($templates) { ?>
		       	<tbody>
		            <?php foreach ($templates as $row) { ?>
	            	<tr>
	              		<td class="text-center">
	              			<?php if ($row['action']) { ?>
							<div class="btn-group">
								<?php if ($row['custom_templates']) { ?>
								<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							    	<i class="fa fa-pencil"></i>
							    	<span class="sr-only">Toggle Dropdown</span>
							  	</button>
							  	<ul class="dropdown-menu" role="menu">
							  		<li><a href="<?php echo $row['action']; ?>"><?php echo $button_default; ?></a></li>
							  		<li role="presentation" class="divider"></li>
							  		<?php foreach($row['custom_templates'] as $row_custom) { ?>
							    	<li><a href="<?php echo $row_custom['action']; ?>"><?php echo $row_custom['emailtemplate_label']; ?></a></li>
							    	<?php } ?>
							  	</ul>
							  	<?php } else { ?>
						  		<a href="<?php echo $row['action']; ?>" class="btn btn-sm"><i class="fa fa-pencil"></i></a>
						  		<?php } ?>
							</div>
	              			<?php } ?>
	              		</td>
	              		<td><?php echo $row['label'] . ($row['custom_count'] ? ' (' . $row['custom_count'] . ')': ''); ?><b class="visible-xs hide"> <?php echo $row['key']; ?></b></td>
	              		<td class="hidden-xs"><b><?php echo $row['key']; ?></b></td>
	              		<td class="text-center"><?php if(isset($row['config'])){ ?><a href="<?php echo $row['config_url']; ?>" style="text-decoration:none; color:inherit"><?php echo $row['config']; ?></a><?php } ?></td>
	              		<td class="text-center hidden-xs"><?php echo $row['modified']; ?></td>
	              		<td class="text-center"><i class="fa fa-<?php echo ($row['shortcodes']) ? 'thumbs-up text-success' : 'thumbs-down text-warning' ?> fa-2x"></i></td>
	              		<td class="text-center"><i class="fa fa-<?php echo ($row['status']) ? 'check-circle text-success' : 'times-circle text-warning' ?> fa-2x"></i></td>
	              		<td class="text-center"><?php if ($row['id'] != 1) { ?><input type="checkbox" name="selected[]" value="<?php echo $row['id']; ?>"<?php if ($row['selected']) { ?> checked="checked"<?php } ?> /><?php } else { ?>&nbsp;<?php } ?></td>
		            </tr>
		            <?php } ?>
				</tbody>
				<tfoot>
					<tr>
	              		<td>&nbsp;</td>
	              		<td>&nbsp;</td>
	              		<td class="hidden-xs">&nbsp;</td>
	              		<td>&nbsp;</td>
	              		<td class="hidden-xs">&nbsp;</td>
	              		<td class="text-center"><div class="btn-group">
							<button class="btn btn-sm btn-warning" data-action="<?php echo $action; ?>&action=delete_shortcode" data-toggle="tooltip" title="<?php echo $button_delete_shortcodes; ?>"><i class="fa fa-thumbs-down"></i></button>
						</div></td>
	              		<td class="text-center"><div class="btn-group">
							<button class="btn btn-sm btn-success" data-action="<?php echo $action; ?>&action=enable" data-toggle="tooltip" title="<?php echo $button_enable; ?>"><i class="fa fa-check-circle"></i></button>
							<button class="btn btn-sm btn-warning" data-action="<?php echo $action; ?>&action=disable" data-toggle="tooltip" title="<?php echo $button_disable; ?>"><i class="fa fa-times-circle"></i></button>
						</div></td>
						<td class="text-center"><button class="btn btn-sm btn-danger" data-confirm="<?php echo $text_confirm; ?>" data-action="<?php echo $action; ?>&action=delete" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash"></i></button></td>
					</tr>
				</tfoot>
				<?php } ?>
			</table>
		</div>

		<div class="row row-spacing">
			<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
			<div class="col-sm-6 text-right"><?php echo $pagination_results; ?></div>
		</div>

		<div class="support-text">
			<h3>Extension Support - <a href="<?php echo $support_url; ?>">open support ticket</a></h3>

			<p>This Extension is brought to you by: <a href="http://www.opencart-templates.co.uk" target="_blank">Opencart-templates</a></p>
		</div>
	</form>
</div>
</div>
<script type="text/javascript"><!--
$('input[name=filter_type]').on('change', function() {
	var url = 'index.php?route=module/emailtemplate&token=<?php echo $token; ?>';

	location = url + '&filter_type=' + encodeURIComponent($(this).val());
});
//--></script>
<?php echo $footer; ?>