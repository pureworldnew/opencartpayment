<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <input type="hidden" name="id" value="<?php if(isset($id)) echo $id; ?>">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <?php foreach ($languages as $language) { $noentry = true;?>
                  <span class="input-group-addon"><img src="" title="<?php echo $language['name']; ?>" /></span>
                  <?php if(isset($name) AND $name){ ?>
                    <?php foreach ($name as $key => $value) { ?>
                      <?php if($key==$language['language_id']){ $noentry = false;?>
                        <input type="text" name="name[<?php echo $language['language_id']; ?>]" value="<?php echo $value; ?>" class="form-control">
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>
                  <?php if($noentry){ ?>
                    <input type="text" name="name[<?php echo $language['language_id']; ?>]" class="form-control">
                  <?php } ?>
                  <br>
                <?php } ?>
              </div>

            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-color"><?php echo $entry_color; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span style="background-color: <?php if(isset($color)) echo $color; ?>;" class="color-pick input-group-addon"></span>
                <input type="text" value="<?php if(isset($color)){ echo $color;}else{ echo '#fffff'; } ?>" name="color" class="colorful form-control" id="input-color">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-model"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name ="status" class="form-control">
              <option value="0" <?php if(isset($status) AND !$status) echo 'selected'; ?> ><?php echo $text_disable; ?></option>
              <option value="1" <?php if(isset($status) AND $status) echo 'selected'; ?>><?php echo $text_enable; ?></option>
            </select>
            </div>
          </div>

      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
$('.color-pick').ColorPicker({
    color: '#fcfcfc',
    onShow: function (colpkr) {
    $(colpkr).fadeIn(500);
    return false;
    },
    onHide: function (colpkr) {
    $(colpkr).fadeOut(500);
    return false;
    },
    onChange: function (hsb, hex, rgb) {
    $('.colorful').val('#' + hex);
    $('.color-pick').css('background-color','#'+hex);
    }
});
</script>
<style type="text/css">
.color-pick {
    cursor: pointer;
}
<?php if(!isset($customer) || !$customer){ ?>
.forDefault{
  display: none;
}
<?php } ?>
<?php if(!isset($admin) || !$admin){ ?>
.forDefaultAdmin{
  display: none;
}
<?php } ?>
</style>
