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
            <?php echo $text_message; ?>
            <?php echo $content_bottom; ?>
	    </div>
        <?php echo $column_right; ?>
    </div>
<?php echo $footer; ?>