<?php echo $header; ?>
<style type="text/css">
<!--

div.scroll {
  height: 200px;
  width: 100%;
  overflow: auto;
  border: 1px solid black;
  background-color: #ccc;
  padding: 8px;
}

a.download {
  font-size: 28px;
  align: justify;
  width: 100%;
}

.list td a.link {
  text-decoration: underline;
  color: blue;
}

#export_status {
  color: black;
}

.scrollbox {
	height: 150px;
	overflow: auto;
}

-->
</style>


<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $discount_update; ?>" target="_blank" class="btn btn-default"><?php echo $button_discount_update; ?></a>
        <button onclick="submitDiscountForm();" form="form-category-discount" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
            <?php if ($success) { ?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>
            <form onsubmit="return ValidateMe()" action="<?php echo $action_export; ?>" method="post" enctype="multipart/form-data" id="form-category-discount" class="col-sm-12">
                <table class="table table-condensed table-striped table-hover">
                <tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Products From Categories'); ?></label></td>
								<td colspan="2">
										<input type="radio" name="products_from_categories" onclick="javascript: showCategories('all');" value="all" <?php if ($params['products_from_categories'] == 'all') { ?> checked="checked" <?php } ?> /><?php echo $this->t('All'); ?>&nbsp;&nbsp;
										<input type="radio" name="products_from_categories" onclick="javascript: showCategories('selected');" value="selected" <?php if ($params['products_from_categories'] == 'selected') { ?> checked="checked" <?php } ?> /><?php echo $this->t('Selected'); ?>&nbsp;&nbsp;
										<input type="radio" name="products_from_categories" onclick="javascript: showCategories('selected');" value="selected_sub" <?php if ($params['products_from_categories'] == 'selected_sub') { ?> checked="checked" <?php } ?> /><?php echo $this->t('Selected with Subcategories'); ?>
								</td>
							</tr>
							<tr id="export_selected_categories" <?php if ($params['products_from_categories'] != 'selected') { ?>style="display:none" <?php } ?>>
								<td width="25%"><label class="control-label"><?php echo $this->t('Categories'); ?></label></td>
								<td colspan="2">
										<div class="well well-sm scrollbox">
											<?php $class = 'odd'; ?>
											<?php foreach ($categories as $category) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div class="<?php echo $class; ?>">
												<?php if (in_array($category['category_id'], $params['category_ids'])) { ?>
												<input type="checkbox" name="category_ids[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
												<?php echo $category['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="category_ids[]" value="<?php echo $category['category_id']; ?>" />
												<?php echo $category['name']; ?>
												<?php } ?>
											</div>
											<?php } ?>
										</div>
										<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $this->t('Select All'); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $this->t('Unselect All'); ?></a>
								</td>
							</tr>
							<tr>
								<td width="25%"><label class="control-label"><?php echo $this->t('Products From Manufacturers'); ?></label></td>
								<td colspan="2">
										<input type="radio" name="products_from_manufacturers" onclick="javascript: showManufacturers('all');" value="all" <?php if ($params['products_from_manufacturers'] == 'all') { ?> checked="checked" <?php } ?> /><?php echo $this->t('All'); ?>&nbsp;&nbsp;
										<input type="radio" name="products_from_manufacturers" onclick="javascript: showManufacturers('selected');" value="selected" <?php if ($params['products_from_manufacturers'] == 'selected') { ?> checked="checked" <?php } ?> /><?php echo $this->t('Selected'); ?>
								</td>
							</tr>
							<tr id="export_selected_manufacturers" <?php if ($params['products_from_manufacturers'] != 'selected') { ?>style="display:none" <?php } ?>>
								<td width="25%"><label class="control-label"><?php echo $this->t('Manufacturers'); ?></label></td>
								<td colspan="2">
										<div class="well well-sm scrollbox">
											<?php $class = 'odd'; ?>
											<?php foreach ($manufacturers as $manufacturer) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div class="<?php echo $class; ?>">
												<?php if (in_array($manufacturer['manufacturer_id'], $params['manufacturer_ids'])) { ?>
												<input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
												<?php echo $manufacturer['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="manufacturer_ids[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
												<?php echo $manufacturer['name']; ?>
												<?php } ?>
											</div>
											<?php } ?>
										</div>
										<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $this->t('Select All'); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $this->t('Unselect All'); ?></a>
								</td>
							</tr>
                    </table>
                    <div class="col-sm-6" id="discounted">
                    <?php $discount_row = 0; ?>
                    <div id="discount-row<?php echo $discount_row; ?>">
                    <table class="form table table-bordered">
                    <tr>
                        <td>
                            <select id="select_cust_group<?php echo $discount_row; ?>" name="select_cust_group[<?php echo $discount_row; ?>][customer_group]" class="form-control" required>
                                <option value="" selected="selected"><?php echo $text_select_cust; ?></option>
                                <?php foreach ($cust_group_list as $cust_list) { ?>
                                    <option value="<?php echo $cust_list['customer_group_id']; ?>"><?php echo $cust_list['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td class="text-left"><button type="button" onclick="addDiscountValues();" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                        <button type="button" onclick="copyDiscountValues(<?php echo $discount_row; ?>);" class="btn btn-default"><i class="fa fa-copy"></i></button>
                        <button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="btn btn-danger" disabled><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    </table>
                    <table class="list table table-bordered col-sm-6" id="discount">
                        <thead>
                            <tr>
                                <td class="right">Quantity:</td>
                                <td class="right">Discount Percentage:</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="right"><input type="text" value="1" name="product_discount[<?php echo $discount_row; ?>][0][quantity]" required class="form-control" id="product_discount_quantity_0<?php echo $discount_row; ?>"></td>
                                <td class="right">
                                    <input type="text" value="8" name="product_discount[<?php echo $discount_row; ?>][0][price]" required class="prod_discount_val form-control" id="product_discount_price_0<?php echo $discount_row; ?>">
                                </td>
                            </tr>
                        
                            <tr>
                                <td class="right"><input type="text" value="10" name="product_discount[<?php echo $discount_row; ?>][1][quantity]" required class="form-control" id="product_discount_quantity_1<?php echo $discount_row; ?>"></td>
                                <td class="right">
                                    <input type="text" value="13" name="product_discount[<?php echo $discount_row; ?>][1][price]" required class="prod_discount_val form-control" id="product_discount_price_1<?php echo $discount_row; ?>">
                                </td>
                            </tr>
                        
                            <tr>
                                <td class="right"><input type="text" value="50" name="product_discount[<?php echo $discount_row; ?>][2][quantity]" required class="form-control" id="product_discount_quantity_2<?php echo $discount_row; ?>"></td>
                                <td class="right">
                                    <input type="text" value="18" name="product_discount[<?php echo $discount_row; ?>][2][price]" required class="prod_discount_val form-control" id="product_discount_price_2<?php echo $discount_row; ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php $discount_row++; ?>
                </div>
            </div>
            <div class="col-sm-8">
            
                <input id="update-discount-submit" type="submit" value="UPDATE DISCOUNTS" class="button" style="background: none repeat scroll 0 0 #003A88;
                border-radius: 10px;
                color: #FFFFFF;
                display: inline-block;
                padding: 5px 15px;
                text-decoration: none;
                cursor: pointer;
                ">                         
            
            </div>
            
      </form>
    </div>
</div>
    
<script>
var discount_row = <?php echo $discount_row; ?>;

function addDiscountValues() { 

    html  = '<div id="discount-row' + discount_row + '"><table class="form table table-bordered"><tr><td>';
    html += '<select id="select_cust_group' + discount_row + '" name="select_cust_group[' + discount_row + '][customer_group]" class="form-control" required><option value="" selected="selected"><?php echo $text_select_cust; ?></option>';
    
    <?php foreach ($cust_group_list as $cust_list) { ?>
        html += '<option value="<?php echo $cust_list[customer_group_id]; ?>"><?php echo $cust_list[name]; ?></option>';
    <?php } ?>
    
    html += '</select></td><td class="text-left"><button type="button" onclick="addDiscountValues();" class="btn btn-primary"><i class="fa fa-plus"></i></button> <button type="button" onclick="copyDiscountValues(' + discount_row +');" class="btn btn-default"><i class="fa fa-copy"></i></button> <button type="button" onclick="$(\'#discount-row' + discount_row +'\').remove();" class="btn btn-danger"><i class="fa fa-trash"></i></button></td></tr></table>';

    html += '<table class="list table table-bordered col-sm-6" id="discount"><thead><tr><td class="right">Quantity:</td><td class="right">Discount Percentage:</td></tr></thead>';


    html += '<tbody><tr><td class="right"><input type="text" value="1" name="product_discount[' + discount_row + '][0][quantity]" required class="form-control" id="product_discount_quantity_0' + discount_row + '"></td><td class="right"><input type="text" value="8" name="product_discount[' + discount_row + '][0][price]" required class="prod_discount_val form-control" id="product_discount_price_0' + discount_row + '"></td></tr>';
                        
    html += '<tr><td class="right"><input type="text" value="10" name="product_discount[' + discount_row + '][1][quantity]" required class="form-control" id="product_discount_quantity_1' + discount_row + '"></td><td class="right"><input type="text" value="13" name="product_discount[' + discount_row + '][1][price]" required class="prod_discount_val form-control" id="product_discount_price_1' + discount_row + '"></td></tr>';
                        
    html += '<tr><td class="right"><input type="text" value="50" name="product_discount[' + discount_row + '][2][quantity]" required class="form-control" id="product_discount_quantity_2' + discount_row + '"></td><td class="right"><input type="text" value="18" name="product_discount[' + discount_row + '][2][price]" required class="prod_discount_val form-control" id="product_discount_price_2' + discount_row + '"></td></tr>';
    
    html += '</tbody></table>';
    discount_row++;
    html += '</div>';
                            
    $('#discounted').append(html);
}

function copyDiscountValues(discount_block)
{
   var current_discount_row = discount_row;
   addDiscountValues();
   $("#product_discount_quantity_0"+current_discount_row).val($("#product_discount_quantity_0"+discount_block).val());
   $("#product_discount_quantity_1"+current_discount_row).val($("#product_discount_quantity_1"+discount_block).val());
   $("#product_discount_quantity_2"+current_discount_row).val($("#product_discount_quantity_2"+discount_block).val());
   $("#product_discount_price_0"+current_discount_row).val($("#product_discount_price_0"+discount_block).val());
   $("#product_discount_price_1"+current_discount_row).val($("#product_discount_price_1"+discount_block).val());
   $("#product_discount_price_2"+current_discount_row).val($("#product_discount_price_2"+discount_block).val());
   var selected = $('#select_cust_group'+discount_block+' :selected').val();
   $('#select_cust_group'+current_discount_row).val(selected);
   
}

function submitDiscountForm()
{
    $( "#update-discount-submit" ).trigger( "click" );
}
    function ValidateMe(){ 
        return true;
    }

function showCategories(id) {

var delay = 300;

if (id == 'all') {
$('#export_selected_categories').fadeOut(delay);
} else if (id == 'selected') {
$('#export_selected_categories').fadeIn(delay);
}
}

function showManufacturers(id) {
var delay = 300;

if (id == 'all') {
$('#export_selected_manufacturers').fadeOut(delay);
} else if (id == 'selected') {
$('#export_selected_manufacturers').fadeIn(delay);
}
}


    </script>
<?php echo $footer; ?>