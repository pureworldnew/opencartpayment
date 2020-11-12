<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-supplier').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
      <div><?php if (file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) { include(DIR_APPLICATION . 'model/module/adv_settings.php'); } else { echo $module_page; } ?></div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-supplier">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right"><?php if ($sort == 'supplier_id') { ?>
                    <a href="<?php echo $sort_supplier_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_supplier_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_supplier_id; ?>"><?php echo $column_supplier_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>                    
                  <td class="text-left"><?php if ($sort == 'email') { ?>
                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                    <?php } ?></td>  
                  <td class="text-right"><?php if ($sort == 'telephone') { ?>
                    <a href="<?php echo $sort_telephone; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_telephone; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_telephone; ?>"><?php echo $column_telephone; ?></a>
                    <?php } ?></td>                                        
                  <td class="text-left"><?php if ($sort == 'website') { ?>
                    <a href="<?php echo $sort_website; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_website; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_website; ?>"><?php echo $column_website; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'products') { ?>
                    <a href="<?php echo $sort_products; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_products; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_products; ?>"><?php echo $column_products; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>                    
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($suppliers) { ?>
                <?php foreach ($suppliers as $supplier) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($supplier['supplier_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-right"><?php echo $supplier['supplier_id']; ?></td>
                  <td class="text-left"><?php echo $supplier['name']; ?></td>
                  <td class="text-left"><?php echo $supplier['email']; ?></td>
                  <td class="text-right"><?php echo $supplier['telephone']; ?></td>
                  <td class="text-left"><?php echo $supplier['website']; ?></td>
                  <td class="text-right"><a href="<?php echo $supplier['link']; ?>"><?php echo $supplier['products']; ?></a></td>
                  <td class="text-left"><?php echo $supplier['status']; ?></td>                  
                  <td class="text-right"><a href="<?php echo $supplier['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div><?php if (!$ldata) { include(DIR_APPLICATION . 'view/image/adv_reports/line.png'); } ?></div>
<?php echo $footer; ?>