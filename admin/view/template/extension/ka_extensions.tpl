<?php
/*
  Project : Ka Extensions
  Author  : karapuz <support@ka-station.com>

  Version : 3 ($Revision: 27 $)
  
*/
?>

<?php echo $header; ?>
<style>
#service_line {
  width: 100%;
  background-color: #EFEFEF;
}

.adv {
  background-color: white;
  color: black;
  font-weight: 12px;
}
</style>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <?php echo $ka_breadcrumbs; ?>
    </div>
  </div>

  <div class="container-fluid">
  
	  <?php echo $ka_top; ?>
  
    <div id="service_line">
			<table>
				<tr>
					<td><b>Ka Extensions Version</b>: <?php echo $extension_version; ?>&nbsp;&nbsp;&nbsp;</td>
					<td><b>Contact Us</b>: <a href="mailto:support@ka-station.com">via email</a>&nbsp;&nbsp;&nbsp;</td>
					<td><a href="https://www.ka-station.com/index.php?route=information/contact" target="_blank">via secure form at www.ka-station.com</a>&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
    </div>
    <br />
    
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-extensions" data-toggle="tab"><?php echo $this->t('Extensions'); ?></a></li>
<?php /*			
			<li><a href="#tab-upgrades" data-toggle="tab"><?php echo $this->t('Upgrades'); ?></a></li>
*/ 
?>			
		</ul>
		<div class="tab-content">		
			<div class="tab-pane active in" id="tab-extensions">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left"><?php echo $column_name; ?></td>
								<td class="text-right"><?php echo $column_action; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php if ($extensions) { ?>
							<?php foreach ($extensions as $extension) { ?>
							<tr>
								<td class="text-left"><?php echo $extension['name']; ?></td>
								<td class="text-right"><?php foreach ($extension['action'] as $action) { ?>
									[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
									<?php } ?></td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<span class="adv">Find more useful extensions at our official <a target="_blank" href="http://www.ka-station.com/ka-extensions?ref=215">www.ka-station.com</a> site</span>
				</div>
			</div>

<?php /*						
			<div class="tab-pane fade" id="tab-upgrades">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left"><?php echo $column_name; ?></td>
								<td class="text-right"><?php echo $column_action; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($upgrades)) { ?>
							<?php foreach ($upgrades as $upgrade) { ?>
							<tr>
								<td class="text-left"><?php echo $upgrade['name']; ?></td>
								<td class="text-right">
									<?php 
										$row = 0;
										foreach ($upgrade['action'] as $action) {
										$row++;
										if ($action['href'] == 'unpack') { 
									?>
										[ <span id="row_spin_<?php echo $row; ?>"></span><a id="row_link_<?php echo $row; ?>" onclick="onUnpack(<?php echo $row; ?>, '<?php echo $upgrade['extension'] ?>');" href="javascript: void();"><?php echo $action['text']; ?></a> ]
									<?php } else { ?>
										[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
									<?php } ?>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>		
*/ 
?>
		</div>

			
  </div>
</div>

<script>

function onUnpack(row, ext_code) {

	$('#modal-input-code').remove();

	$.ajax({
		url: 'index.php?route=extension/ka_extensions/inputCode&token=' + getURLVar('token') + '&extension=' + ext_code,
		dataType: 'html',
		beforeSend: function() {
			$('#row_spin_' + row).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
			$('#row_link_' + row).hide();
		},
		complete: function() {
			$('#row_spin_' + row).html('');
			$('#row_link_' + row).show();
		},
		success: function(html) {
			$('body').append('<div id="modal-input-code" class="modal">' + html + '</div>');
			$('#modal-input-code').modal('show');
		}
	});	

}

</script>

<?php echo $footer; ?>