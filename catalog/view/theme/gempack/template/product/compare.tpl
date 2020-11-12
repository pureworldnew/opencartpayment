<?php echo $header; ?>
	<div class="row">
        <?php echo $column_left; ?>
<?php
if ($column_left and $column_right) {
    $class="col-lg-8 col-md-6 col-sm-4 col-xs-12";
} elseif ($column_left or $column_right) {
     $class="col-lg-10 col-md-9 col-sm-8 col-xs-12";
} else {
     $class="col-xs-12";
}
?>
        <div class="<?php echo $class; ?>" id="content">
            <?php echo $content_top; ?>
            <ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
            </ul>
            <h1><?php echo $heading_title; ?></h1>
<?php if ($products) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td colspan="<?php echo count($products) + 1; ?>"><strong><?php echo $text_product; ?></strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $text_name; ?></td>
<?php foreach ($products as $product) { ?>
                        <td><a href="<?php echo $product['href']; ?>"><strong><?php echo $product['name']; ?></strong></a></td>
<?php } ?>
                    </tr>
                    <tr>
                        <td><?php echo $text_image; ?></td>
<?php foreach ($products as $product) { ?>
                        <td class="text-center"><?php if ($product['thumb']) { ?><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /><?php } ?></td>
<?php } ?>
                    </tr>
                    <tr>
                        <td><?php echo $text_price; ?></td>
<?php foreach ($products as $product) { ?>
                        <td><?php if ($product['price']) { ?><?php if (!$product['special']) { ?><?php echo $product['price']; ?><?php } else { ?><strike><?php echo $product['price']; ?></strike> <?php echo $product['special']; ?><?php } ?><?php } ?></td>
<?php } ?>
                    </tr>
                    <tr>
                        <td><?php echo $text_model; ?></td>
<?php foreach ($products as $product) { ?>
                        <td><?php echo $product['model']; ?></td>
<?php } ?>
                    </tr>
                    <tr>
                        <td><?php echo $text_availability; ?></td>
<?php foreach ($products as $product) { ?>
                        <td><?php echo $product['availability']; ?></td>
<?php } ?>
                    </tr>
                    <tr>
                        <td><?php echo $text_summary; ?></td>
<?php foreach ($products as $product) { ?>
                        <td class="description"><?php echo $product['description']; ?></td>
<?php } ?>
                    </tr>
                </tbody>
<?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                    <tr>
                        <td colspan="<?php echo count($products) + 1; ?>"><strong><?php echo $attribute_group['name']; ?></strong></td>
                    </tr>
                </thead>
<?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
                <tbody>
                    <tr>
                        <td><?php echo $attribute['name']; ?></td>
<?php foreach ($products as $product) { ?>
<?php if (isset($product['attribute'][$key])) { ?>
                        <td><?php echo $product['attribute'][$key]; ?></td>
<?php } else { ?>
                        <td></td>
<?php } ?>
<?php } ?>
                    </tr>
                </tbody>
<?php } ?>
<?php } ?>
                <tr>
                    <td></td>
<?php foreach ($products as $product) { ?>
                    <td>
                        <input type="button" value="<?php echo $button_cart; ?>" class="btn btn-primary btn-block" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');" />
                        <a href="<?php echo $product['remove']; ?>" class="btn btn-danger btn-block"><?php echo $button_remove; ?></a>
                    </td>
<?php } ?>
                </tr>
            </table>
<?php } else { ?>
            <p><?php echo $text_empty; ?></p>
<?php } ?>
            <?php echo $content_bottom; ?>
	    </div>
        <?php echo $column_right; ?>
    </div>
<?php echo $footer; ?>