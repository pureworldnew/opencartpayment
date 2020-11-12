<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            	
               <a onclick="saveContinue();" class="btn btn-primary"><?php echo $button_save_continue; ?></a>
               <a onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></a>
               <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></a>
                
            	
            </div>
                <h1><?php echo $heading_title; ?></h1>
     			<ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
        </div>
        	<?php if ( count($errors) > 0 ) { ?>
            	<ul class="alert alert-danger col-sm-10 col-sm-offset-1">
                	<?php foreach($errors as $error) { ?>
                    	<li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
          	<?php } ?>
          	<?php if (!empty($success)) { ?>
          		<div class="alert alert-success col-sm-10 col-sm-offset-1"><?php echo $success; ?></div>
         	<?php } ?>
      </div>

    <div class="container-fluid">
      <!--<div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a></div>-->
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <!--<div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>-->
          
          <div id="language<?php echo $language['language_id']; ?>">
             <?php if(!empty($product_info)){ ?>
             	<table class="table table-bordered table-hover">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td><input  class="form-control"  type="text" name="name" size="100" value="<?php echo $product_info['name'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_sku; ?></td>
                <td><input  class="form-control"  type="text" name="sku" size="100" value="<?php echo $product_info['sku'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group indicator'; ?></td>
                <td><input  class="form-control" type="text" name="groupindicator" size="100" value="<?php echo $product_info['groupindicator'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group indicator id'; ?></td>
                <td><input  class="form-control" type="text" name="groupindicator_id" size="100" value="<?php echo $product_info['groupindicator_id'];?>" /></td>
              </tr>
              
              <tr>
                <td><span class="required">*</span> <?php echo 'product id'; ?></td>
                <td><input  class="form-control" type="text" name="product_id" size="100" value="<?php echo $product_info['product_id'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group by name'; ?></td>
                <td><input  class="form-control" type="text" name="groupbyname" size="100" value="<?php echo $product_info['groupbyname'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group by value'; ?></td>
                <td><input  class="form-control" type="text" name="groupbyvalue" size="100" value="<?php echo $product_info['groupbyvalue'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name1'; ?></td>
                <td><input  class="form-control" type="text" name="optionname1" size="100" value="<?php echo $product_info['optionname1'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option value1'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue1" size="100" value="<?php echo $product_info['optionvalue1'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name2'; ?></td>
                <td><input  class="form-control" type="text" name="optionname2" size="100" value="<?php echo $product_info['optionname2'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option value2'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue2" size="100" value="<?php echo $product_info['optionvalue2'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name3'; ?></td>
                <td><input  class="form-control" type="text" name="optionname3" size="100" value="<?php echo $product_info['optionname3'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option value3'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue3" size="100" value="<?php echo $product_info['optionvalue3'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name4'; ?></td>
                <td><input  class="form-control" type="text" name="optionname4" size="100" value="<?php echo $product_info['optionname4'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value4'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue4" size="100" value="<?php echo $product_info['optionvalue4'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name5'; ?></td>
                <td><input  class="form-control" type="text" name="optionname5" size="100" value="<?php echo $product_info['optionname5'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value5'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue5" size="100" value="<?php echo $product_info['optionvalue5'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name6'; ?></td>
                <td><input  class="form-control" type="text" name="optionname6" size="100" value="<?php echo $product_info['optionname6'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value6'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue6" size="100" value="<?php echo $product_info['optionvalue6'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name7'; ?></td>
                <td><input  class="form-control" type="text" name="optionname7" size="100" value="<?php echo $product_info['optionname7'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value7'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue7" size="100" value="<?php echo $product_info['optionvalue7'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name8'; ?></td>
                <td><input  class="form-control" type="text" name="optionname8" size="100" value="<?php echo $product_info['optionname8'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value8'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue8" size="100" value="<?php echo $product_info['optionvalue8'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name9'; ?></td>
                <td><input  class="form-control" type="text" name="optionname9" size="100" value="<?php echo $product_info['optionname9'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value9'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue9" size="100" value="<?php echo $product_info['optionvalue9'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name10'; ?></td>
                <td><input  class="form-control" type="text" name="optionname10" size="100" value="<?php echo $product_info['optionname10'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value10'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue10" size="100" value="<?php echo $product_info['optionvalue10'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name11'; ?></td>
                <td><input  class="form-control" type="text" name="optionname11" size="100" value="<?php echo $product_info['optionname11'];?>" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value11'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue11" size="100" value="<?php echo $product_info['optionvalue11'];?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'grouped product name'; ?></td>
                <td><input  class="form-control" type="text" name="groupedproductname" size="100" value="<?php echo $product_info['groupedproductname'];?>" /></td>
              </tr>
              
            </table>
             <?php }else{ ?>
            	<table class="table table-bordered table-hover">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td><input  class="form-control"  type="text" name="name" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_sku; ?></td>
                <td><input  class="form-control"  type="text" name="sku" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group indicator'; ?></td>
                <td><input  class="form-control" type="text" name="groupindicator" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group indicator id'; ?></td>
                <td><input  class="form-control" type="text" name="groupindicator_id" size="100" value="" /></td>
              </tr>
              
              <tr>
                <td><span class="required">*</span> <?php echo 'product id'; ?></td>
                <td><input  class="form-control" type="text" name="product_id" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group by name'; ?></td>
                <td><input  class="form-control" type="text" name="groupbyname" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'group by value'; ?></td>
                <td><input  class="form-control" type="text" name="groupbyvalue" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name1'; ?></td>
                <td><input  class="form-control" type="text" name="optionname1" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option value1'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue1" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name2'; ?></td>
                <td><input  class="form-control" type="text" name="optionname2" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option value2'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue2" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name3'; ?></td>
                <td><input  class="form-control" type="text" name="optionname3" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option value3'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue3" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name4'; ?></td>
                <td><input  class="form-control" type="text" name="optionname4" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value4'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue4" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name5'; ?></td>
                <td><input  class="form-control" type="text" name="optionname5" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value5'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue5" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name6'; ?></td>
                <td><input  class="form-control" type="text" name="optionname6" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value6'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue6" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name7'; ?></td>
                <td><input  class="form-control" type="text" name="optionname7" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value7'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue7" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name8'; ?></td>
                <td><input  class="form-control" type="text" name="optionname8" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value8'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue8" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name9'; ?></td>
                <td><input  class="form-control" type="text" name="optionname9" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value9'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue9" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name10'; ?></td>
                <td><input  class="form-control" type="text" name="optionname10" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value10'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue10" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'option name11'; ?></td>
                <td><input  class="form-control" type="text" name="optionname11" size="100" value="" /></td>
              </tr>
<tr>
                <td><span class="required">*</span> <?php echo 'option value11'; ?></td>
                <td><input  class="form-control" type="text" name="optionvalue11" size="100" value="" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo 'grouped product name'; ?></td>
                <td><input  class="form-control" type="text" name="groupedproductname" size="100" value="" /></td>
              </tr>
              
            </table>
             <?php }?>
          </div>
           
        </div>
        
        <!-- inizio -->
			<input  class="form-control" type="hidden" name="id" size="100" value="<?php echo $product_info['id'];?>" />
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace("description<?php echo $language['language_id']; ?>", {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});
//--></script> 

<?php if (VERSION > '1.5.4.1') { ?>
<script type="text/javascript"><!--
// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.manufacturer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'manufacturer\']').attr('value', ui.item.label);
		$('input[name=\'manufacturer_id\']').attr('value', ui.item.value);
	
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

// Category
$('input[name=\'category\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-category' + ui.item.value).remove();
		
		$('#product-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input  class="form-control" type="hidden" name="product_category[]" value="' + ui.item.value + '" /></div>');

		$('#product-category div:odd').attr('class', 'odd');
		$('#product-category div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-category div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-category div:odd').attr('class', 'odd');
	$('#product-category div:even').attr('class', 'even');	
});

// Filter
$('input[name=\'filter\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.filter_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-filter' + ui.item.value).remove();
		
		$('#product-filter').append('<div id="product-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input  class="form-control" type="hidden" name="product_filter[]" value="' + ui.item.value + '" /></div>');

		$('#product-filter div:odd').attr('class', 'odd');
		$('#product-filter div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-filter div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-filter div:odd').attr('class', 'odd');
	$('#product-filter div:even').attr('class', 'even');	
});

// Downloads
$('input[name=\'download\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.download_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-download' + ui.item.value).remove();
		
		$('#product-download').append('<div id="product-download' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input  class="form-control" type="hidden" name="product_download[]" value="' + ui.item.value + '" /></div>');

		$('#product-download div:odd').attr('class', 'odd');
		$('#product-download div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-download div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-download div:odd').attr('class', 'odd');
	$('#product-download div:even').attr('class', 'even');	
});
//--></script> 
<?php } /*END VERSION*/ ?>

<script type="text/javascript"><!--
// Related
$('input[name=\'related\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product-related' + ui.item.value).remove();
		
		$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input  class="form-control" type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');

		$('#product-related div:odd').attr('class', 'odd');
		$('#product-related div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#product-related div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-related div:odd').attr('class', 'odd');
	$('#product-related div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
	html  = '<tbody id="attribute-row' + attribute_row + '">';
    html += '  <tr>';
	html += '    <td class="left"><input  class="form-control" type="text" name="product_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '    <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += "<textarea name=\"product_attribute[" + attribute_row + "][product_attribute_description][<?php echo $language['language_id']; ?>][text]\" cols=\"40\" rows=\"5\"></textarea><img src=\"view/image/flags/<?php echo $language['image']; ?>\" title=\"<?php echo $language['name']; ?>\" align=\"top\" /><br />";
    <?php } ?>
	html += '    </td>';
	html += '    <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
    html += '  </tr>';	
    html += '</tbody>';
	
	$('#attribute tfoot').before(html);
	
	attributeautocomplete(attribute_row);
	
	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
      		return false;
   		}
	});
}

$('#attribute tbody').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input  class="form-control"  type="hidden" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
	html += '    <td class="right"><input  class="form-control"  type="text" name="product_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#images tfoot').before(html);
	
	image_row++;
}
//--></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
$('#vtab-option a').tabs();
//--></script>

<script type="text/javascript"><!--
function saveContinue(){$('#form').submit();}
function wopen(aHref){window.open(aHref,'','width=950,height=550,scrollbars=1,resizable=1,toolbar=0,menubar=0');}
//--></script>
<script type="text/javascript"><!--
function putStartingPrice(spVal){$('input[name="price"]').val(spVal);}
//--></script>
<script type="text/javascript"><!--
$('.child-search').each(function(){
var theName = $(this).attr('name');
var theFilter = $(this).attr('data-childfilter');
var filterInput = $('input[name="' + theName + '"]');
if(theFilter=="name"){var theMinLength="<?php echo $this->config->get('min_chars_search_product_name'); ?>";}else{var theMinLength=1;}
filterInput.autocomplete({
	minLength:theMinLength,
	delay:500,
	source:function(request, response){
		$.ajax({
			url: "index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_"+theFilter+"=" + encodeURIComponent("%"+request.term),
			dataType: "json",
			beforeSend: function(){filterInput.css("background","#fff url('view/image/loading.gif') no-repeat center");},
			complete: function(){filterInput.css("background","#fff");},
			success: function(json){
				response($.map(json,function(item){
					if(item.model != 'grouped'){return{
						label: (theFilter=="name") ? item.name : item.model,
						image: item.image ? item.image : "",
						name: item.name,
						model: item.model,
						price: item.price,
						value: item.product_id
					}}
				}));
			}
		});
	}, 
	select:function(event, ui) {
		$('#product-child' + ui.item.value).remove();
		$('input[name="price"]').val('');
		
		html  = '<tbody id="product-child' + ui.item.value + '"><tr>';
		html += '  <td class="left"><input  class="form-control"  type="text" size="1" name="group_list[' + ui.item.value + '][grouped_maximum]" value="0" /></td>';
		html += '  <td class="center"><img src="' + ui.item.image + '" /></td>';
		html += '  <td class="left"><input  class="form-control"  type="hidden" name="group_list[' + ui.item.value + '][grouped_id]" value="' + ui.item.value + '" />' + ui.item.name + '</td>';
		html += '  <td class="left">' + ui.item.model + '</td>';
		html += '  <td class="right" style="border-right:none;"><input  class="form-control"  type="radio" name="is_starting_price" id="is_starting_price' + ui.item.value + '" value="' + ui.item.value + '" onclick="putStartingPrice(' + ui.item.price + ');" /></td>';
		html += '  <td class="right"><label for="is_starting_price' + ui.item.value + '">' + ui.item.price + '</label></td>';
		html += '  <td class="left"> </td>';
		html += '  <td class="left"><select name="group_list[' + ui.item.value + '][pgvisibility]"><option value="1"><?php echo $text_visible; ?></option><option value="2"><?php echo $text_invisible_searchable; ?></option><option value="0"><?php echo $text_invisible; ?></option></select></td>';
		html += '  <td class="left"><input  class="form-control"  type="text" name="group_list[' + ui.item.value + '][product_sort_order]" value="" size="2" /></td>';
		html += '  <td class="left"><select name="group_list[' + ui.item.value + '][grouped_stock_status_id]"><option value="0"> </option>';
		               <?php foreach($stock_statuses as $stock_status){ if($this->config->get('default_product_nocart') == $stock_status['stock_status_id']){ ?>
		               html += '<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>';
		               <?php }else{ ?>
		               html += '<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>';
		               <?php }} ?>
        html += '    </select></td>';
		html += '  <td class="left"><a onclick="$(\'#product-child' + ui.item.value + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '</tr></tbody>';
		
		$('#product-child tfoot').before(html);
		return false;
	},
	focus: function(event, ui) {
      return false;
    }
}).data("autocomplete")._renderItem=function(ul,item){
	var theregex= new RegExp("("+this.term.split(" ").join("|")+")","gi");return $("<li></li>").data("item.autocomplete",item).append('<a style="white-space:nowrap;"><img src="'+item.image+'" style="vertical-align:middle;" /> '+item.label.replace(theregex,'<strong style="color:#C00;">$1</strong>')+'</a>').appendTo(ul);
};});
//var theregex= new RegExp("(?![^&;]+;)(?!<[^<>]*)("+this.term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi,"\\$1")+")(?![^<>]*>)(?![^&;]+;)","gi");
//--></script>
<?php echo $footer; ?>