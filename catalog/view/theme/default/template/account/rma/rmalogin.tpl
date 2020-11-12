<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?></div>
  <?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <?php if(isset($logintype)){ ?>
            <legend><?php echo $text_login_type; ?></legend>
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $text_login_info; ?></div>

            <div class="form-group required">
              <label class="col-sm-2 control-label"><?php echo $text_login_type; ?></label>
              <div class="col-sm-10">
                <div class="radio">
                  <input type="radio" name="logintype" value="1" id="registered"> <label for="registered"><?php echo $text_registered; ?></label>
                </div>
                <div class="radio">
                  <input type="radio" name="logintype" value="0" id="guest"> <label for="guest"><?php echo $text_guest; ?></label>
                </div>
              </div>
            </div>
          <?php }elseif(isset($guestlogin)){ ?>
            <legend><?php echo $text_order_info; ?></legend>
            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $text_guest_info; ?></div>

            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-orderinfo"><?php echo $text_orderid; ?></label>
              <div class="col-sm-10">
                <input type="text" name="orderinfo" class="form-control" id="input-orderinfo" value="<?php echo $orderinfo; ?>">
              </div>
            </div>

            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-email"><?php echo $text_email; ?></label>
              <div class="col-sm-10">
                <input type="text" name="email" id="input-email" class="form-control" value="<?php echo $email; ?>">
              </div>
            </div>
          <?php } ?>
          <input class="btn btn-primary pull-right" type="submit" value="<?php echo $button_request; ?>" class="button"/>
        </fieldset>
      </form>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
