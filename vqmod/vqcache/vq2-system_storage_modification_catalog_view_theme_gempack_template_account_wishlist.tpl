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
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($products) { ?>
		 <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-center hidden-xs" style="width:70px;"><?php echo $column_image; ?></td>
            <td class="text-left"><?php echo $column_name; ?></td>
            <td class="text-left hidden-xs"><?php echo $column_model; ?></td>
            <td class="text-right hidden-xs"><?php echo $column_stock; ?></td>
            <td class="text-right hidden-xs"><?php echo $column_price; ?></td>
            <td class="text-right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="text-center hidden-xs"><?php if ($product['thumb']) { ?><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a><?php } ?></td>
            <td class="text-left">
                <div class="hidden-xs"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                <div class="hidden-lg hidden-md hidden-sm">
                    <div class="pull-left" style="width:70px; margin-right:10px;">
                        <?php if ($product['thumb']) { ?><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" /></a><?php } ?>
                    </div>
                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br />
                    <strong><?php echo $column_model; ?>:</strong> <?php echo $product['model']; ?><br />
                    <strong><?php echo $column_stock; ?>:</strong> <?php echo $product['stock']; ?><br />
                    <?php if ($product['price']) { ?><strong><?php echo $column_price; ?>:</strong> <?php if (!$product['special']) { ?><?php echo $product['price']; ?><?php } else { ?><b><?php echo $product['special']; ?></b> <s><?php echo $product['price']; ?></s><?php } ?><?php } ?>
                </div>
            </td>
            <td class="text-left hidden-xs"><?php echo $product['model']; ?></td>
            <td class="text-right hidden-xs"><?php echo $product['stock']; ?></td>
            <td class="text-right hidden-xs"><?php if ($product['price']) { ?>
              <div class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <b><?php echo $product['special']; ?></b> <s><?php echo $product['price']; ?></s>
                <?php } ?>
              </div>
              <?php } ?></td>
            <td class="text-right"><button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_cart; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></button>
              <a href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
		</div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
<?php echo $footer; ?>