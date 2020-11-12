
<!--	<?php //if($hover_bgcolor){ ?>
    <style type="text/css">
		ul.ui-autocomplete > li:hover{
		    background-color: #<?php //echo $hover_bgcolor; ?>;
		}
		ul.ui-autocomplete > li{
			color: #<?php //echo $text_color;?>;
		}
        </style>
		<?php //} ?>-->

<div class="box search_block">
<script type="text/javascript" src="catalog/view/javascript/ecquickbuy/common.js?v=34"></script>
<script type="text/javascript">
$(document).ready(function() {
	var languages = {text_price:"<?php echo $text_price;?>",
					 text_viewall:"<?php echo $text_viewall; ?>"};
	var options = {show_image:<?php echo ($show_image==1)?'true':'false';?>,
				   show_price:<?php echo ($show_price==1)?'true':'false';?>,
				   show_viewmore:<?php echo ($all_result==1)?'true':'false';?>,
				   search_sub_category:<?php echo ($search_sub_category==1)?'1':'0';?>,
				   search_description:<?php echo ($search_description==1)?'1':'0';?>,
				   reload: <?php echo $reload?'true':'false'; ?>,
				   base_url: "<?php echo isset($base)?$base:'';?>",
				   quickview_title: "<?php echo $text_quickview;?>",
				   popup_width: "<?php echo ($popup_width)?$popup_width:'50%';?>",
				   popup_height: "<?php echo ($popup_height)?$popup_height:'550px';?>",
				   link_more: "index.php?route=product/search&search={search}{manufacturer_id}{category_id}&sub_category=<?php echo ($search_sub_category==1)?'true':'false';?>&description=<?php echo ($search_description==1)?'true':'false';?>"
				  }
	initAutocomplete('#addtocart<?php echo $module ?>', languages, options);
})
</script>
	<?php if($show_title){ ?>
         <div class="quy-head"><?php echo $text_ecquickbuy_title; ?></div>
            <p class="quy-top-text">Enter Sku,Reference #, or Keyword</p>
           
	<?php } ?>
	<div class="box-content input_search_desk">
		<form method="GET" action="<?php echo $search_link; ?>">
			<input type="hidden" name="route" value="product/search"/>
			<?php if($view_direction == 'horizontal'){ ?>
			<table  style= "width:100%;" class="quickbuy">
				<thead>
					<tr>
						<?php if(!empty($manufacturers)){ ?>
						<td><?php //echo $text_manufacturer;?></td>
						<?php } ?>
						<?php if(!empty($categories)) { ?>
						<td><?php //echo $text_category;?></td>
						<?php } ?>
						<td><?php //echo $text_filter_product;?></td>
						<?php if(!empty($show_qty)) { ?>
						<td><?php //echo $text_quantity;?></td>
						<?php } ?>
						<td></td>
					</tr>
				</thead>
				<tbody>
				<tr id="addtocart<?php echo $module ?>">
					<?php /* if(!empty($manufacturers)){ ?>
					<td><select name="manufacturer_id">
							<option value="0"><?php echo $text_all_manufacturer; ?></option>
							<?php foreach($manufacturers as $manufacturer){ ?>
								<option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
							<?php } ?>
						</select></td>
					<?php } */ ?>
					<?php /* if(!empty($categories)) { ?>
					<td>
						<select name="category_id">
							<option value="0"><?php echo $text_category_all; ?></option>
							<?php foreach ($categories as $category_1) { ?>
					        <?php if ($category_1['category_id'] == $category_id) { ?>
					        <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
					        <?php } else { ?>
					        <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
					        <?php } ?>
					        <?php if(isset($category_1['children'])) { ?>
					        <?php foreach ($category_1['children'] as $category_2) { ?>
					        <?php if ($category_2['category_id'] == $category_id) { ?>
					        <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
					        <?php } else { ?>
					        <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
					        <?php } ?>
					        <?php if(isset($category_2['children'])){?>
					        <?php foreach ($category_2['children'] as $category_3) { ?>
					        <?php if ($category_3['category_id'] == $category_id) { ?>
					        <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
					        <?php } else { ?>
					        <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
						</select></td>
					<?php } */ ?>
					<td><input type="text" value="" size="<?php echo $input_width;?>" autocomplete="off" placeholder="<?php echo $text_search_product;?>" name="search"></td>
					<?php if(!empty($show_qty)) { ?>
                                        <td class="no-space"><input type="text" value="" name="quantity"  placeholder="Qty" class="quy"/></td>
					<?php } ?>
                                        <td class="no-space">
						<a rel="" href="" class="quickview"></a>
						<input type="button" onclick="addQuickBuy('#addtocart<?php echo $module ?>')" class="button btn-buy" value="<?php echo $text_add_to_cart;?>"></td>
				</tr>
				</tbody>
			</table>
			<?php } else { ?>
			<ul id="addtocart<?php echo $module ?>" class="fields">
				<?php if(!empty($manufacturers)){ ?>
				<li class="label"><?php echo $text_manufacturer;?></li>
				<li class="value"><select name="manufacturer_id">
							<option value="0"><?php echo $text_all_manufacturer; ?></option>
							<?php foreach($manufacturers as $manufacturer){ ?>
								<option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
							<?php } ?>
						</select></li>
				<?php } ?>
				<?php if(!empty($categories)) { ?>
				<li class="label"><?php echo $text_category;?></li>
				<li class="value"><select name="category_id">
							<option value="0"><?php echo $text_category_all; ?></option>
							<?php foreach ($categories as $category_1) { ?>
					        <?php if ($category_1['category_id'] == $category_id) { ?>
					        <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
					        <?php } else { ?>
					        <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
					        <?php } ?>
					        <?php if(isset($category_1['children'])) { ?>
					        <?php foreach ($category_1['children'] as $category_2) { ?>
					        <?php if ($category_2['category_id'] == $category_id) { ?>
					        <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
					        <?php } else { ?>
					        <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
					        <?php } ?>
					        <?php if(isset($category_2['children'])){?>
					        <?php foreach ($category_2['children'] as $category_3) { ?>
					        <?php if ($category_3['category_id'] == $category_id) { ?>
					        <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
					        <?php } else { ?>
					        <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
					        <?php } ?>
						</select></li>
				<?php } ?>
				<li class="label">
					<?php echo $text_filter_product;?>
				</li>
				<li class="value"><input type="text" value="" size="<?php echo $input_width;?>" autocomplete="off" placeholder="<?php echo $text_search_product;?>" name="search"></li>
				<?php if(!empty($show_qty)) { ?>
				<li class="label"><?php echo $text_quantity;?></li>
				<?php } ?>
				<li class="value"><input type="text" value="" name="quantity" size="5" placeholder="1"/></li>
				<li><a rel="" href="" class="quickview"></a>
						<input type="button" onclick="addQuickBuy('#addtocart<?php echo $module ?>')" class="button btn-buy" value="<?php echo $text_add_to_cart;?>"></li>
			</ul>
			<?php } ?>
	</form>
	<div class="clear"></div>
	</div>
	</div>

