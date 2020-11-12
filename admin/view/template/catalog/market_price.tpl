<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            	
                <a href="<?php echo $db_cron; ?>" target="_blank" class="btn btn-default"><?php echo $button_db_cron; ?></a>
                <a href="<?php echo $discount_update; ?>" target="_blank" class="btn btn-default"><?php echo $button_discount_update; ?></a>
                <a onclick="$('form').submit();" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></a>
                <a href="javascript:void(0);" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>	
            </div>
                <h1><?php echo $heading_title; ?></h1>
        </div>
        	<?php if (isset($error_warning)) { ?>
          		<div class="warning"><?php echo $error_warning; ?></div>
          	<?php } ?>
          	<?php if (isset($success)) { ?>
          		<div class="success"><?php echo $success; ?></div>
         	<?php } ?>
      </div>
<div class="container-fluid">
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
   <table class="table table-bordered table-hover">
        <thead class='list'>
            <th><?php echo $text_name; ?></th>
            <th><?php echo $heading_title; ?></th>
        </thead>
        <tbody>
            <?php foreach ($cats as $cat) {?>
            <tr>
                <td class='left'><?php echo $cat['name'];?></td>
                <td><input type="text" name='market_price[<?php echo $cat['name'];?>]' value="<?php echo round($cat['market_price'], 5); ?>"></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
</div>
</div>
<?php echo $footer;?>
