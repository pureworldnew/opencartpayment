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
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<h2>License code</h2>
      Please enter your Opencart SEO Pack PRO license code: 
      <input type="text" name="l" value="<?php echo $l;?>" size="50"/> 
	<span class="help">License code is the OrderID of the order you purchased Opencart SEO Pack PRO. It can be found in your account on opencart.com -> My Account -> Order History (first column).</span><br><br>
	<h2>About Opencart SEO Pack PRO</h2>
	v.8.46<br><br>
	<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182&version=1&from=<?php echo $_SERVER['SERVER_NAME'];?>">Get the latest version</a> / 
	<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182&purchase=1&from=<?php echo $_SERVER['SERVER_NAME'];?>">Purchase Opencart SEO Pack PRO</a>
	
	
    </form>
  </div>
</div>

<?php echo $footer; ?>