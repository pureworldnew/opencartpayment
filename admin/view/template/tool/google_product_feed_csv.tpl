<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($warning) { ?>
  <div class="warning"><?php echo $warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/log.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_download; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $download; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="form">
			<tr>
              <td><?php echo $entry_date_from; ?></td>
              <td><input type="text" name="date_from" value="<?php echo $date_from; ?>" size="12" class="date" /></td>
			</tr>
			<tr>
              <td><?php echo $entry_date_to; ?></td>
              <td><input type="text" name="date_to" value="<?php echo $date_to; ?>" size="12" class="date" /></td>
			</tr>
		</table>
	  </form>
    </div>
  </div>
</div>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 

<?php echo $footer; ?>