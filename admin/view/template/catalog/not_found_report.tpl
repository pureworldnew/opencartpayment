<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"><link type="text/css" href="view/stylesheet/stylesheet2.css" rel="stylesheet" media="screen" />
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
	<div class="buttons"><a onclick="location = '<?php echo $redirect; ?>';" class="button">Create Redirect</a>
	<a onclick="location.href='<?php echo $clearlog; ?>';" class="button"  class="btn btn-danger">Clear 404s Report</a></div>    
  </div>
  <div class="content">

	 <table class="list">
          <thead>
            <tr>              
              <td class="left">Not Found Link</td>
              <td class="left">Last Date</td>              
            </tr>
          </thead>
          <tbody>
            
            <?php if (!empty($pages)) { ?>
            <?php foreach ($pages as $page) { ?>
            <tr>              
              <td class="left"><?php echo $page['link']; ?></td>
              <td class="left"><?php echo $page['date']; ?></td>                            			  
            </tr>
			<?php } ?>			
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
		</table>
		 <div class="pagination"><?php echo $pagination; ?></div>
	</div>

	<span style="color:red" class="help">*</span>	
  </div>
</div>

<?php echo $footer; ?>