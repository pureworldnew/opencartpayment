<?php echo $header; ?>

                <link type="text/css" rel="stylesheet" href="catalog/view/css/account.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/ele-style.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/dashboard.css" />
			

  <div class="row"><?php echo $column_left; ?>
             <?php
if ($column_left and $column_right) {
    $class="col-lg-8 col-md-6 col-sm-4 col-xs-12";
} elseif ($column_left or $column_right) {
     $class="col-lg-10 col-md-9 col-sm-8 col-xs-12";
} else {
     $class="col-xs-12";
}
?>

        
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/bootstrap.min.css">
		
      
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
        <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
      
      <div class="row-fluid">
            <div class="span6">
              <h2><?php echo $text_new_customer; ?></h2>
                        <p><strong><?php echo $text_register; ?></strong></p>
                        <p><?php echo $text_register_account; ?></p>
                        <a href="<?php echo $register; ?>" class="button"  id="button-account"><?php echo $button_continue; ?></a></div>
            
            <div id="login" class="span6 login_checkout">
              <div class="well">
                        <h2><?php echo $text_returning_customer; ?></h2>
                        <p><strong><?php echo $text_i_am_returning_customer; ?></strong></p>
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                          <div class="form-group">
                            <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                            <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                          </div>
                          <div class="form-group">
                            <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                            <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                            <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
                          <input type="hidden" name="current_route" value="account/login">
                          <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
                          <?php if ($redirect) { ?>
                          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                          <?php } ?>
                        </form>
                      </div>
            </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?>
  </div>
<?php echo $footer; ?>
