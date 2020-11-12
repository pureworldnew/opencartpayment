<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  
  <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            	<a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="$('form').submit();"><?php echo $button_delete; ?></button>
                
            	
            </div>
                <h1><?php echo $heading_title; ?></h1>
              <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    
     
        </div>
        	<?php if (isset($error_warning)) { ?>
          		<div class="warning"><?php echo $error_warning; ?></div>
          	<?php } ?>
          	<?php if (isset($success)) { ?>
          		<div class="success"><?php echo $success; ?></div>
         	<?php } ?>
      </div>
  

    
   <div class="container-fluid">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="text-left"><?php if ($sort == 'u.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php if ($sort == 'u.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($units) { ?>
            <?php foreach ($units as $unit) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($unit['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $unit['unit_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $unit['unit_id']; ?>" />
                <?php } ?></td>
              <td class="text-left"><?php echo $unit['name']; ?></td>
              <td class="text-right"><?php echo $unit['sort_order']; ?></td>
              <td class="text-right"><?php foreach ($unit['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>

</div>
<?php echo $footer; ?>