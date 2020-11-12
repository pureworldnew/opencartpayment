<?php echo $header; ?>

<script type="text/javascript">
<!--
function googleTaxonomy() {
	var href='http://www.google.com/basepages/producttype/taxonomy.<?php echo $google_product_feed_lang; ?>.txt';
	window.open(href,"GoogleTaxonomy");
}
</script>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title . ' (' . ($no_of_products + 1) . ' ' . ($no_of_products == 0 ? $text_product : $text_products) . ')'; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_update; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $update; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab_feed">
	  <input type="hidden" value="<?php echo $selected_products; ?>" name="selected_products" />
        <table class="form">
		<tr>
			<td colspan="2" style="background: #ffffcc"><?php echo $text_instructions_1 . $text_clear_keyword . $text_instructions_2; ?></td>
		</tr>
		<tr>
			<td colspan="2"><?php echo $text_general; ?></td>
		</tr>
		<tr>
            <td width="40%"><?php echo $entry_list; ?></td>
            <td width="60%"><select name="gpf_status">
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
              </select></td>
			</td>
          </tr>
		 <tr>
			<td><?php echo $entry_google_product_category; ?></td>
			<td><input type="text" name="google_product_category" style="width:400px;" value="<?php echo $google_product_category; ?>" />
			<a onclick = "googleTaxonomy()" title="click here to select a category">
			<img src="view/image/plus.png" width="16" height=" 16" style="margin: 15px 15px 0;"></a>
			</td>
		  </tr>
		<tr>
         <tr>
            <td width="40%"><?php echo $entry_brand; ?></td>
            <td width="60%"><input type="text" name="brand" id="brand" value="<?php echo $brand; ?>" /><a onclick="clearField('brand')"><img src="view/image/clear.png" width="16" height=" 16"  alt="clear" title="click here to clear this field" style="margin-left:40px;"></a>
			</td>
          </tr>

<!-- GTIN IS NOT INCLUDED BECAUSE THIS FIELD SHOULD BE UNIQUE FOR EACH PRODUCT
          <tr>
            <td><?php echo $entry_gtin; ?></td>
            <td><input type="text" name="gtin" id="gtin" value="<?php echo $gtin; ?>" /><a onclick="clearField('gtin')"><img src="view/image/clear.png" width="16" height=" 16" alt="clear" title="click here to clear this field" style="margin-left:40px;"></a></td>
          </tr>
					-->
					
		  <tr>
            <td><?php echo $entry_mpn; ?></td>
            <td><input type="text" name="mpn" id="mpn" value="<?php echo $mpn; ?>" /><a onclick="clearField('mpn')"><img src="view/image/clear.png" width="16" height=" 16" alt="clear" title="click here to clear this field" style="margin-left:40px;"></a></td>
          </tr>
		  <tr>
            <td><?php echo $entry_gender; ?></td>
					<td><select name="gender">
					  <?php foreach($available_genders as $av_genders) { ?>
					  <?php if ($gender == $av_genders) { ?>
					  <option value="<?php echo $av_genders; ?>" selected="selected"><?php echo $av_genders; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $av_genders; ?>"><?php echo $av_genders; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select></td>
          </tr>
		  <tr>
            <td><?php echo $entry_agegroup; ?></td>
					<td><select name="agegroup">
					  <?php foreach($available_agegroup as $av_age) { ?>
					  <?php if ($agegroup == $av_age) { ?>
					  <option value="<?php echo $av_age; ?>" selected="selected"><?php echo $av_age; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $av_age; ?>"><?php echo $av_age; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select></td>
          </tr>
		  <tr>
            <td><?php echo $entry_colour; ?></td>
            <td><input type="text" name="colour" id="colour" value="<?php echo $colour; ?>" /><a onclick="clearField('colour')"><img src="view/image/clear.png" width="16" height=" 16" alt="clear" title="click here to clear this field" style="margin-left:40px;"></a></td>
          </tr>
		  <tr>
            <td><?php echo $entry_size; ?></td>
            <td><input type="text" name="size" id="size" value="<?php echo $size; ?>" /><a onclick="clearField('size')"><img src="view/image/clear.png" width="16" height=" 16" alt="clear" title="click here to clear this field" style="margin-left:40px;"></a></td>
          </tr>
		  <tr>
					<td><?php echo $entry_condition; ?></td>
					<td><select name="condition">
					  <?php foreach($available_conditions as $av_cond) { ?>
					  <?php if ($condition == $av_cond) { ?>
					  <option value="<?php echo $av_cond; ?>" selected="selected"><?php echo $av_cond; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $av_cond; ?>"><?php echo $av_cond; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select></td>
          </tr>
		  <tr>
					<td><?php echo $entry_oos_status; ?></td>
					<td><select name="oos_status">
					  <?php foreach($available_oos_statuses as $av_oos) { ?>
					  <?php if ($oos_status == $av_oos) { ?>
					  <option value="<?php echo $av_oos; ?>" selected="selected"><?php echo $av_oos; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $av_oos; ?>"><?php echo $av_oos; ?></option>
					  <?php } ?>
					  <?php } ?>
					</select></td>
		  </tr>
		  
		  <tr>
			<td><?php echo $entry_identifier_exists; ?></td>
			<td><select name="identifier_exists" style="float: left; margin-right: 50px;">
			  
			  <?php if ($identifier_exists == 'FALSE') { ?>
				  <option value="TRUE"><?php echo $text_true; ?></option>
				  <option value="FALSE" selected="selected"><?php echo $text_false; ?></option>
			  <?php } else { ?>
				  <option value="TRUE" selected="selected"><?php echo $text_true; ?></option>
				  <option value="FALSE"><?php echo $text_false; ?></option>
			  <?php } ?>
			</select>
			</td>
		  </tr>
       </table>
 
        <table class="form">
          <tr>
            <td colspan="2"><?php echo $entry_variant; ?></td>
          </tr>
            <tr>
              <td width="40%"><?php echo $entry_clear_variants; ?></td>
              <td width="60%">
                <input type="radio" name="clear_variants" value="1" checked="checked" />
                <?php echo $text_ignore; ?>
                <input type="radio" name="clear_variants" value="0" />
                <?php echo $text_clear; ?>
			  </td>
            </tr>
		</table>
		
        <table id="variants" class="list">
		  <thead>
			<tr>
				<td width="20%" style="text-align:center">Size</td>
				<td width="20%" style="text-align:center">Colour</td>
				<td width="20%" style="text-align:center">Pattern</td>
				<td width="20%" style="text-align:center">Material</td>
				<td width="12%" style="text-align:center">&nbsp;</td>
			</tr>
			</thead>
			
			<?php $variants_row = 0; 
			if ($product_variants){
			
			?>
			
			<?php foreach ($product_variants as $product_variant) { ?>

			<tbody id="variants_row<?php echo $variants_row; ?>">
			<tr>
				<td width="20%" style="text-align:center"><input type="text" name="product_variants[<?php echo $variants_row; ?>][size]" tabindex="<?php echo ($variants_row*4+12); ?>" value="<?php echo $product_variant['size']; ?>" /></td>
				<td width="20%" style="text-align:center"><input type="text" name="product_variants[<?php echo $variants_row; ?>][colour]" tabindex="<?php echo ($variants_row*4+13); ?>" value="<?php echo $product_variant['colour']; ?>" /></td>
				<td width="20%" style="text-align:center"><input type="text" name="product_variants[<?php echo $variants_row; ?>][pattern]" tabindex="<?php echo ($variants_row*4+14); ?>" value="<?php echo $product_variant['pattern']; ?>" /></td>
				<td width="20%" style="text-align:center"><input type="text" name="product_variants[<?php echo $variants_row; ?>][material]" tabindex="<?php echo ($variants_row*4+15); ?>" value="<?php echo $product_variant['material']; ?>" /></td>
				<input type="hidden" name="product_variants[<?php echo $variants_row; ?>][image]" value="" />
		        <td class="left"><a onclick="$('#variants_row<?php echo $variants_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
			</tr>
			</tbody>
			<?php $variants_row++; ?>
			<?php } 
			} ?>
		  <tfoot>
			<tr>
			  <td colspan="4"></td>
			  <td class="left"><a onclick="addVariant();" class="button"><span><?php echo $button_add_variant; ?></span></a></td>
			</tr>
		  </tfoot>
		</table>

		<table class="form">
          <tr>
            <td ><?php echo $entry_adwords; ?></td>
            <td >&nbsp;</td>
          </tr>
		<tr>
            <td width="40%"><?php echo $entry_adwords_grouping; ?></td>
            <td width="60%">
                <input type="text" name="adwords_grouping" id="adwords_grouping" style="width:400px;" value="<?php echo $adwords_grouping; ?>" />
				<a onclick="clearField('adwords_grouping')"><img src="view/image/clear.png" width="16" height=" 16" alt="clear" title="click here to clear this field" style="margin-left:40px;"></a>
              </td>
			</td>
          </tr>
		<tr>
            <td width="40%"><?php echo $entry_adwords_labels; ?></td>
            <td width="60%">
                <input type="text" name="adwords_labels" id="adwords_labels" style="width:400px;" value="<?php echo $adwords_labels; ?>" />
				<a onclick="clearField('adwords_labels')"><img src="view/image/clear.png" width="16" height=" 16" alt="clear" title="click here to clear this field" style="margin-left:40px;"></a>
              </td>
			</td>
          </tr>
		<tr>
            <td width="40%"><?php echo $entry_adwords_redirect; ?></td>
            <td width="60%"><select name="adwords_redirect">
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
              </select></td>
			</td>
          </tr>
		</table>


		<table class="form">
          <tr>
            <td style="background: #ffffcc"><?php echo $entry_information; ?></td>
          </tr>
		</table>

        <table id="products" class="list">
		  <thead>
			<tr>
				<td colspan="3"><?php echo ($entry_products . '(' . ($no_of_products + 1) . ')'); ?></td>
			</tr>
			</thead>
			
			<?php $ctr = 0; ?>
			<?php while ($ctr <= $no_of_products) { ?>

			<tr>
				<td width="33%" style="text-align:center; padding: 8px;">
				<?php echo ('<span style="color: #00c;"><strong>' . $products[$ctr]['name'] . '</strong></span><br />' . $products[$ctr]['model']); ?></td>

				<td width="34%" style="text-align:center; padding: 8px;">
				<?php if(isset($products[$ctr+1])) {echo ('<span style="color: #00c;"><strong>' . $products[$ctr+1]['name'] . '</strong></span><br />' . $products[$ctr+1]['model']);} ?></td>

				<td width="33%" style="text-align:center; padding: 8px;">
				<?php if(isset($products[$ctr+2])) {echo ('<span style="color: #00c;"><strong>' . $products[$ctr+2]['name'] . '</strong></span><br />' . $products[$ctr+2]['model']);} ?></td>
			</tr>



			<?php $ctr = $ctr+3 ?>
			<?php } ?>
		</table>

	</div>



      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var variants_row = <?php echo $variants_row; ?>;

function addVariant() {
	html  = '<tbody id="variants_row' + variants_row + '">';
	html += '<tr>'; 
    html += '<td width="20%" style="text-align:center"><input type="text" name="product_variants[' + variants_row +'][size]" tabindex="' + (variants_row*4+12) +'" /></td>';
	html += '<td width="20%" style="text-align:center"><input type="text" name="product_variants[' + variants_row +'][colour]" tabindex="' + (variants_row*4+12) +'" /></td>';
    html += '<td width="20%" style="text-align:center"><input type="text" name="product_variants[' + variants_row +'][pattern]" tabindex="' + (variants_row*4+12) +'" /></td>';
	html += '<td width="20%" style="text-align:center"><input type="text" name="product_variants[' + variants_row +'][material]" tabindex="' + (variants_row*4+12) +'" /></td>';
	html += '<input type="hidden" name="product_variants[' + variants_row +'][image]" value="" />';
	html += '<td class="left"><a onclick="$(\'#variants_row' + variants_row +'\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';	
    html += '</tbody>';
	$('#variants tfoot').before(html);
		
	variants_row++;
}

//--></script>

<script type="text/javascript">
<!--
function clearField(fld) {
		var el = document.getElementById(fld);
		el.value = '<?php echo $text_clear_keyword; ?>';
}
//--></script>

<script type="text/javascript">
<!--
function checkVariants() {
		alert('checkVariants');
		if(!document.form.size_variants[]) {
			alert('No size variants');
		}
//		$('#form').submit();
}
//--></script>

<script type="text/javascript">
<!--
	function encode_variants(size_data, colour_data, pattern_data, material_data) {
		separator = '|';
		variant = '';
		for (var i = 0; i < size_data.length; i++) {
			if(size_data[i] != '' || colour_data[i] !='' || pattern_data[i] !='' || material_data[i] !='') {
				if(variant != '') {
					variant += separator;
				}
				variant += size + separator + colour_data[i] + separator + pattern_data[i] + separator + material_data[i];
				i++;
			}
		}
		return variant;
	}

	
//--></script>
	
<?php echo $footer; ?>