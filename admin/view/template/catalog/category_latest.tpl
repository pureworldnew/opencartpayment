<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      <a href="<?php echo $update_latest_category_products; ?>" data-toggle="tooltip" title="Update Latest Category Products" class="btn btn-success">Update Latest Category Products</a> 
        <button type="submit" form="form-latest-cat" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
      </div>
      <h1>Latest Category Settings</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Latest Category Settings</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-latest-cat" class="form-horizontal">
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><span data-toggle="tooltip" title="Set the limit for how many products you want to be displayed in your latest category.">Product Limit</span></label>
            <div class="col-sm-10">
              <input type="text" name="product_limit" value="<?php echo $product_limit; ?>" placeholder="Product Limit" id="input-limit" class="form-control" />
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-unlink-old"><span data-toggle="tooltip" title="Unlink old products from latest category">Unlink Old Products</span></label>
            <div class="col-sm-10">
              <select name="unlink_old_products" id="input-unlink-old" class="form-control">
                <option value="1" <?php if($unlink_old_products==1) { echo "selected"; } ?>>Yes</option>
                <option value="0" <?php if($unlink_old_products==0) { echo "selected"; } ?>>No</option>
               </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
