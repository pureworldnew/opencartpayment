<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            		<button type="submit" form="form" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save"><i class="fa fa-save"></i></button>
            		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a></div>
                <h1><?php echo $heading_title; ?></h1>
              <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    
     
        </div>
      </div>
  <div class="container-fluid">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered table-hover">
          <tr>
            <td width="20%"><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td width="40%">
              <input class="form-control" type="text" name="unit_name" value="<?php echo isset($unit_name) ? $unit_name : ''; ?>" />
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input class="form-control" type="text" name="sort_order" value="<?php echo isset($unit_sortorder) ? $unit_sortorder : ''; ?>" size="1" /></td>
          </tr>
        </table>
        <table id="unit-value" class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="left"><span class="required">*</span> <?php echo $entry_unit_value; ?></td>
              <?php /*<td class="left"><?php echo $entry_convert_price; ?></td> */?>
              <td class="left"><?php echo $entry_sort_order; ?></td>
              <td class="center">-</td>
            </tr>
          </thead>
          <?php $unit_value_row = 0; ?>
          <?php foreach ($unit_values as $unit_value) { ?>
          <tbody id="unit-value-row<?php echo $unit_value_row; ?>">
            <tr>
              <td class="left">
                  <input class="form-control" type="text" name="unit_value[<?php echo $unit_value_row; ?>][name]" value="<?php echo $unit_value['unit_value_name']; ?>" size="30" />
                  <input class="form-control" type="hidden" name="unit_value[<?php echo $unit_value_row; ?>][unit_value_id]" value="<?php echo $unit_value['unit_value_id']; ?>" size="1" />
              </td>
              <?php /*<td class="left"><input type="text" name="unit_value[<?php echo $unit_value_row; ?>][convert_price]" value="<?php echo $unit_value['convert_price']; ?>" size="20" /></td>*/?>
              <td class="left"><input class="form-control"  type="text" name="unit_value[<?php echo $unit_value_row; ?>][sort_order]" value="<?php echo $unit_value['sort_order']; ?>" size="1" /></td>
              <td class="centre"><a onclick="$('#unit-value-row<?php echo $unit_value_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $unit_value_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addUnitValue();" class="button"><?php echo $add_unit_value; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'type\']').bind('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'image') {
		$('#unit-value').show();
	} else {
		$('#unit-value').hide();
	}
});

var unit_value_row = <?php echo $unit_value_row; ?>;

function addUnitValue() {
	html  = '<tbody id="unit-value-row' + unit_value_row + '">';
	html += '  <tr>';	
        html += '    <td class="left"><input class="form-control" type="text" name="unit_value[' + unit_value_row + '][name]" value="" size="30" />';
	html += '    </td>';
	//html += '    <td class="left"><input type="text" name="unit_value[' + unit_value_row + '][convert_price]" value="" size="20" /></td>';
	html += '    <td class="left"><input class="form-control" type="text" name="unit_value[' + unit_value_row + '][sort_order]" value="" size="1" /></td>';
	html += '    <td class="centre"><a onclick="$(\'#unit-value-row' + unit_value_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';	
        html += '</tbody>';
	
	$('#unit-value tfoot').before(html);
	
	unit_value_row++;
}
//--></script> 
<?php echo $footer; ?>