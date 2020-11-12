<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customtotal" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customtotal" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="customtotal_status" id="input-status" class="form-control">
                <?php if ($customtotal_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-allowfront"><?php echo $entry_allowfront; ?></label>
            <div class="col-sm-10">
              <select name="customtotal_allowfront" id="input-allowfront" class="form-control">
                <?php if ($customtotal_allowfront) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="customtotal_sort_order" value="<?php echo $customtotal_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customtotaltext; ?></td>
                      <td class="text-right" style="width:20%;"><?php echo $entry_customtotalamount; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                <?php $range_value_row = 0; ?>
                    <?php foreach ($customtotal_names as $range_value) { ?>
                    <tr id="range-value-row<?php echo $range_value_row; ?>">
                    <?php foreach ($languages as $language) { ?>
                    <td class="input-group"><span class="input-group-addon"><?php echo $language['name']; ?></span>
                      <input type="text" name="customtotal_names[<?php echo $range_value_row; ?>][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($range_value[$language['language_id']]) ? $range_value[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_customtotaltext; ?>" class="form-control" />
                    </td>
                    <?php } ?>
                      <td class="text-right"><input type="text" name="customtotal_names[<?php echo $range_value_row; ?>][amount]" value="<?php echo $range_value['amount']; ?>" class="form-control" placeholder="<?php echo $entry_customtotalamount; ?>" /></td>
                      <td class="text-left"><button type="button" onclick="$('#range-value-row<?php echo $range_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $range_value_row++; ?>
                    <?php } ?>
                    </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="6"></td>
                      <td class="text-left"><button type="button" onclick="addOptionValue('<?php echo $range_value_row; ?>');" data-toggle="tooltip" title="Add Range" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
 <script type="text/javascript"><!--
var range_value_row = <?php echo $range_value_row; ?>;

function addOptionValue(option_row) {

  html  = '<tr id="range-value-row' + range_value_row + '">';
  <?php foreach ($languages as $language) { ?>
  html += ' <td class="text-right input-group"><span class="input-group-addon"><?php echo $language['name']; ?></span>';
  html += '<input type="text" name="customtotal_names[' + range_value_row + '][<?php echo $language['language_id']; ?>][name]" value="" placeholder="Enter Custom Total" class="form-control" placeholder="<?php echo $entry_customtotaltext; ?>"  /></td>';
  <?php } ?>
  html += '  <td class="text-right"><input type="text" name="customtotal_names[' + range_value_row + '][amount]" value="" placeholder="<?php echo $entry_customtotalamount; ?>" class="form-control"  placeholder="<?php echo $entry_customtotalamount; ?>" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#range-value-row' + range_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('tbody').append(html);
        $('[rel=tooltip]').tooltip();

  range_value_row++;
}
//--></script>
<?php echo $footer; ?>