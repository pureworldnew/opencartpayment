<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-supermenu" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
<script type="text/javascript" src="view/cpicker/jquery.miniColors.js"></script>
<link rel="stylesheet" type="text/css" href="view/cpicker/jquery.miniColors.css" />
<script type="text/javascript">
			function startColMan() {
			$(".colors").miniColors({
					
					change: function(hex, rgb) {
						$("#console").prepend('HEX: ' + hex + ' (RGB: ' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')<br />');
					}
					
				});
			}
			$(document).ready(startColMan);
			
		</script>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
	  <?php $this->model('tool/image'); ?>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-supermenu" class="form-horizontal">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-items" data-toggle="tab"><?php echo $tab_items; ?></a></li>
				<li><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
				<li><a href="#tab-html" data-toggle="tab"><?php echo $tab_html; ?></a></li>
            </ul>
			<div class="tab-content">
				<div class="tab-pane active in" id="tab-items">
				  <div class="table-responsive">
					<table id="item" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left"><?php echo $entry_type; ?></td>
								<td style="width: 46%;" class="text-left"><?php echo $entry_iset; ?></td>
								<td class="text-left"><?php echo $entry_advanced; ?></td>
								<td></td>
							</tr>
						</thead>
						<?php $item_row = 0; ?>
						<?php $libg = '#fcfcfc'; ?>
						<?php foreach ($items as $item) { ?>
								<?php $libg = ($libg == '#fff' ? '#fcfcfc' : '#fff'); ?>
								<tbody class="item-row" id="item-row<?php echo $item_row; ?>">
									<tr>
										<td class="text-left" style="background: <?php echo $libg; ?>;">
											<select style="border: 4px solid <?php if ($item['type'] == 'cat') { ?>#C85555<?php } elseif ($item['type'] == 'infol') { ?>#7593D9<?php } elseif ($item['type'] == 'infod') { ?>#D49ECA<?php } elseif ($item['type'] == 'custom') { ?>#F4D98E<?php } elseif ($item['type'] == 'mand') { ?>#A0C183<?php } elseif ($item['type'] == 'products') { ?>#8F6E96<?php } elseif ($item['type'] == 'catprods') { ?>#C5B15F<?php } elseif ($item['type'] == 'login') { ?>#9DC58D<?php } else { ?>#80ACAB<?php } ?>;" class="item-type" id="item-type<?php echo $item_row; ?>" name="supermenu_item[<?php echo $item_row; ?>][type]">
												<?php if ($item['type'] == 'cat') { ?>
													<option class="ocat" id="ocat<?php echo $item_row; ?>" value="cat" selected="selected"><?php echo $type_cat; ?></option>
												<?php } else { ?>
													<option class="ocat" id="ocat<?php echo $item_row; ?>" value="cat"><?php echo $type_cat; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'infol') { ?>
													<option class="oinfol" id="oinfol<?php echo $item_row; ?>" value="infol" selected="selected"><?php echo $type_infol; ?></option>
												<?php } else { ?>
													<option class="oinfol" id="oinfol<?php echo $item_row; ?>" value="infol"><?php echo $type_infol; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'infod') { ?>
													<option value="infod" selected="selected"><?php echo $type_infod; ?></option>
												<?php } else { ?>
													<option value="infod"><?php echo $type_infod; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'custom') { ?>
													<option class="ocustom" id="ocustom<?php echo $item_row; ?>" value="custom" selected="selected"><?php echo $type_custom; ?></option>
												<?php } else { ?>
													<option class="ocustom" id="ocustom<?php echo $item_row; ?>" value="custom"><?php echo $type_custom; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'mand') { ?>
													<option class="mand" id="mand<?php echo $item_row; ?>" value="mand" selected="selected"><?php echo $type_mand; ?></option>
												<?php } else { ?>
													<option class="mand" id="mand<?php echo $item_row; ?>" value="mand"><?php echo $type_mand; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'more') { ?>
													<option class="more" id="more<?php echo $item_row; ?>" value="more" selected="selected"><?php echo $type_more; ?></option>
												<?php } else { ?>
													<option class="more" id="more<?php echo $item_row; ?>" value="more"><?php echo $type_more; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'more2') { ?>
													<option class="moredoi" id="moredoi<?php echo $item_row; ?>" value="more2" selected="selected"><?php echo $type_more2; ?></option>
												<?php } else { ?>
													<option class="moredoi" id="moredoi<?php echo $item_row; ?>" value="more2"><?php echo $type_more2; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'products') { ?>
													<option class="oproducts" id="oproducts<?php echo $item_row; ?>" value="products" selected="selected"><?php echo $type_products; ?></option>
												<?php } else { ?>
													<option class="oproducts" id="oproducts<?php echo $item_row; ?>" value="products"><?php echo $type_products; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'catprods') { ?>
													<option class="catprods" id="catprods<?php echo $item_row; ?>" value="catprods" selected="selected"><?php echo $type_catprods; ?></option>
												<?php } else { ?>
													<option class="catprods" id="catprods<?php echo $item_row; ?>" value="catprods"><?php echo $type_catprods; ?></option>
												<?php } ?>
												<?php if ($item['type'] == 'login') { ?>
													<option class="login" id="login<?php echo $item_row; ?>" value="login" selected="selected"><?php echo $type_login; ?></option>
												<?php } else { ?>
													<option class="login" id="login<?php echo $item_row; ?>" value="login"><?php echo $type_login; ?></option>
												<?php } ?>
											</select>
											<div class="viewsel viewsel<?php echo $item_row; ?>"><b><?php echo $text_sview; ?> </b>
												<select name="supermenu_item[<?php echo $item_row; ?>][view]">
													<?php if (!$item['view']) { ?>
														<option value="0" selected="selected"><?php echo $text_slist; ?></option>
													<?php } else { ?>
														<option value="0"><?php echo $text_slist; ?></option>
													<?php } ?>
													<?php if ($item['view'] == '1') { ?>
														<option value="1" selected="selected"><?php echo $text_sgrid; ?></option>
													<?php } else { ?>
														<option value="1"><?php echo $text_sgrid; ?></option>
													<?php } ?>
													<?php if ($item['view'] == 'f0') { ?>
														<option value="f0" selected="selected"><?php echo $text_dflist; ?></option>
													<?php } else { ?>
														<option value="f0"><?php echo $text_dflist; ?></option>
													<?php } ?>
													<?php if ($item['view'] == 'f1') { ?>
														<option value="f1" selected="selected"><?php echo $text_dfgrid; ?></option>
													<?php } else { ?>
														<option value="f1"><?php echo $text_dfgrid; ?></option>
													<?php } ?>
												</select>
											</div>
										</td>
										<td class="text-left" style="background: <?php echo $libg; ?>;">
											<div style="position: relative">
												<div class="sorder">
														<?php echo $text_sorder; ?>
														<input size="2" type="text" name="supermenu_item[<?php echo $item_row; ?>][sorder]" value="<?php echo isset($item['sorder']) ? $item['sorder'] : ''; ?>" />
												</div>
												<div id="cat-type<?php echo $item_row; ?>" class="typediv cat-type">
													<h3 class="superh3"><?php echo $entry_category; ?></h3>
													<select style="max-width: 210px;" name="supermenu_item[<?php echo $item_row; ?>][category_id]">
														<?php foreach ($categories as $category ){ ?>
															<?php if ($category['category_id'] == $item['category_id']) { ?>
																<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name'] ?></option>
															<?php } else { ?>
																<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name'] ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</div>
		 
												<div id="info-type<?php echo $item_row; ?>" class="typediv info-type">
														<h3 class="superh3"><?php echo $entry_information; ?></h3>
														<select style="max-width: 210px;" name="supermenu_item[<?php echo $item_row; ?>][information_id]">
															<?php foreach ($informations as $information){ ?>
																<?php if ($information['information_id'] == $item['information_id']) { ?>
																	<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['name'] ?></option>
																<?php } else { ?>
																	<option value="<?php echo $information['information_id']; ?>"><?php echo $information['name'] ?></option>
																<?php } ?>
															<?php } ?>
														</select>
												</div>
		   
												<div id="oproducts-type<?php echo $item_row; ?>" class="typediv oproducts-type">
														<h3 class="superh3"><?php echo $text_whatproducts; ?></h3>
														<?php if (!isset($item['products'])) { $item['products'] = ''; } ?>
														<select style="max-width: 210px;" name="supermenu_item[<?php echo $item_row; ?>][products]">
															<?php if ($item['products'] == 'latest') { ?>
																<option  value="latest" selected="selected"><?php echo $text_productlatest; ?></option>
															<?php } else { ?>
																<option value="latest"><?php echo $text_productlatest; ?></option>
															<?php } ?>
															<?php if ($item['products'] == 'special') { ?>
																<option  value="special" selected="selected"><?php echo $text_productspecial; ?></option>
															<?php } else { ?>
																<option value="special"><?php echo $text_productspecial; ?></option>
															<?php } ?>
															<?php if ($item['products'] == 'featured') { ?>
																<option  value="featured" selected="selected"><?php echo $text_productfeatured; ?></option>
															<?php } else { ?>
																<option value="featured"><?php echo $text_productfeatured; ?></option>
															<?php } ?>
															<?php if ($item['products'] == 'bestseller') { ?>
																<option  value="bestseller" selected="selected"><?php echo $text_productbestseller; ?></option>
															<?php } else { ?>
																<option value="bestseller"><?php echo $text_productbestseller; ?></option>
															<?php } ?>
														</select>
												</div>	

												<div id="productlimit-type<?php echo $item_row; ?>" class="typediv productlimit-type">
													<?php echo $text_productlimit; ?> <input size="1" type="text" name="supermenu_item[<?php echo $item_row; ?>][productlimit]" value="<?php echo isset($item['productlimit']) ? $item['productlimit'] : '5'; ?>" />
												</div>
												
												<div id="custom-type<?php echo $item_row; ?>" class="typediv custom-type">
													<h3 class="superh3"><?php echo $entry_custom; ?></h3>
													<?php foreach ($languages as $language) { ?>
														<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $custom_name; ?>
														<input type="text" name="supermenu_item[<?php echo $item_row; ?>][customname][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['customname'][$language['language_id']]) ? $item['customname'][$language['language_id']] : ''; ?>" /><br /><br />
														<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $custom_url; ?>
														<input type="text" name="supermenu_item[<?php echo $item_row; ?>][customurl][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['customurl'][$language['language_id']]) ? $item['customurl'][$language['language_id']] : ''; ?>" /><br /><br />
													<?php } ?>
												</div>
		  
												<h3 class="addsuperh3" id="hideadd-type<?php echo $item_row; ?>"><?php echo $entry_add; ?></h3>
												<div id="add-type<?php echo $item_row; ?>" class="addtypediv">
													<?php foreach ($languages as $language) { ?>
														<div style="padding: 3px; background: #eee; border: 1px solid #ddd; margin-bottom: 2px; text-align: left; font-weight: bold;"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
															URL: <input type="text" name="supermenu_item[<?php echo $item_row; ?>][addurl][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['addurl'][$language['language_id']]) ? $item['addurl'][$language['language_id']] : ''; ?>" />
															
															<div>
																<a href="" id="thumb-image_<?php echo $item_row; ?><?php echo $language['language_id']; ?>" data-toggle="image" class="img-thumbnail">
																	<img style="max-width: 200px" src="<?php if ($item['image'][$language['language_id']] && strlen($item['image'][$language['language_id']]) > 3) { ?><?php echo HTTP_CATALOG . "image/" . $item['image'][$language['language_id']]; ?><?php } else { ?><?php echo $no_image; ?><?php } ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" />
																</a>
																<input type="hidden" name="supermenu_item[<?php echo $item_row; ?>][image][<?php echo $language['language_id']; ?>]" value="<?php echo $item['image'][$language['language_id']]; ?>" id="input-image_<?php echo $item_row; ?><?php echo $language['language_id']; ?>" />
															</div>
         
														</div>
													<?php } ?>
												</div>
												<script type="text/javascript"><!--
													superh3stat<?php echo $item_row; ?> = $('#hideadd-type<?php echo $item_row; ?>').hasClass('active');
														$('#hideadd-type<?php echo $item_row; ?>').bind('click', function() {
														if (!superh3stat<?php echo $item_row; ?>) {
															$("#add-type<?php echo $item_row; ?>").slideDown('fast');
															$(this).addClass('active');
															superh3stat<?php echo $item_row; ?> = true;
														} else {
															$("#add-type<?php echo $item_row; ?>").slideUp('fast');
															$(this).removeClass('active');
															superh3stat<?php echo $item_row; ?> = false;
														}
													});
												//--></script> 
											</div>
										</td>
										<td class="text-left" style="background: <?php echo $libg; ?>;">
											<style type="text/css">
												.advm<?php echo $item_row; ?> .advh {
													display:none;
												}
											</style>
											<div class="advm<?php echo $item_row; ?> advs">
												<a class="expand"><?php echo $text_expando; ?></a>
												<div class="advh">
													<a class="closeadvm"><strong><span style="font-size:20px;"><span style="font-family:lucida sans unicode,lucida grande,sans-serif;">x</span></span></strong></a>
													<table class="smenua">
														<tr><td><?php echo $text_tlcolor; ?></td>
															<td><input type="text" name="supermenu_item[<?php echo $item_row; ?>][tlcolor]" class="colors" value="<?php echo isset($item['tlcolor']) ? $item['tlcolor'] : ''; ?>" size="7" /></td>
															<td><?php echo $text_tlstyle; ?></td>
															<td><input type="text" name="supermenu_item[<?php echo $item_row; ?>][tlstyle]" value="<?php echo isset($item['tlstyle']) ? $item['tlstyle'] : ''; ?>" size="28" /></td>
														</tr>
														<tr>
															<td><?php echo $text_chtml; ?></td>
																<td><select name="supermenu_item[<?php echo $item_row; ?>][chtml]">
																	<?php if (!$item['chtml']) { ?>
																		<option value="0" selected="selected"><?php echo $text_no; ?></option>
																		<option value="1"><?php echo $text_alldrop; ?></option>
																		<option value="2"><?php echo $text_justadd; ?></option>
																		<option value="3"><?php echo $text_overdrop; ?></option>
																	<?php } elseif ($item['chtml'] == 1) { ?>
																		<option value="1"  selected="selected"><?php echo $text_alldrop; ?></option>
																		<option value="3"><?php echo $text_overdrop; ?></option>
																		<option value="2"><?php echo $text_justadd; ?></option>
																		<option value="0"><?php echo $text_no; ?></option>
																	<?php } elseif ($item['chtml'] == 3) { ?>
																		<option value="0"><?php echo $text_no; ?></option>
																		<option value="1"><?php echo $text_alldrop; ?></option>
																		<option value="3" selected="selected"><?php echo $text_overdrop; ?></option>
																		<option value="2"><?php echo $text_justadd; ?></option>
																	<?php } else { ?>
																		<option value="1"><?php echo $text_alldrop; ?></option>
																		<option value="3"><?php echo $text_overdrop; ?></option>
																		<option value="2"  selected="selected"><?php echo $text_justadd; ?></option>
																		<option value="0"><?php echo $text_no; ?></option>
																	<?php } ?>
																	</select>	
																	<select name="supermenu_item[<?php echo $item_row; ?>][cchtml]">
																		<?php if ($item['cchtml'] == 'area1') { ?>
																			<option value="area1" selected="selected">Area 1</option>
																		<?php } else { ?>
																			<option value="area1">Area 1</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area2') { ?>
																			<option value="area2" selected="selected">Area 2</option>
																		<?php } else { ?>
																			<option value="area2">Area 2</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area3') { ?>
																			<option value="area3" selected="selected">Area 3</option>
																		<?php } else { ?>
																			<option value="area3">Area 3</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area4') { ?>
																			<option value="area4" selected="selected">Area 4</option>
																		<?php } else { ?>
																			<option value="area4">Area 4</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area5') { ?>
																			<option value="area5" selected="selected">Area 5</option>
																		<?php } else { ?>
																			<option value="area5">Area 5</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area6') { ?>
																			<option value="area6" selected="selected">Area 6</option>
																		<?php } else { ?>
																			<option value="area6">Area 6</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area7') { ?>
																			<option value="area7" selected="selected">Area 7</option>
																		<?php } else { ?>
																			<option value="area7">Area 7</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area8') { ?>
																			<option value="area8" selected="selected">Area 8</option>
																		<?php } else { ?>
																			<option value="area8">Area 8</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area9') { ?>
																			<option value="area9" selected="selected">Area 9</option>
																		<?php } else { ?>
																			<option value="area9">Area 9</option>
																		<?php } ?>
																		<?php if ($item['cchtml'] == 'area10') { ?>
																			<option value="area10" selected="selected">Area 10</option>
																		<?php } else { ?>
																			<option value="area10">Area 10</option>
																		<?php } ?>
																	</select>
															</td>
															<td><?php echo $text_subcatdisplay; ?></td>
															<?php if (!isset($item['subcatdisplay'])) { $item['subcatdisplay'] = ''; } ?>
															<td><select name="supermenu_item[<?php echo $item_row; ?>][subcatdisplay]">
																	<?php if ($item['subcatdisplay'] == 'all') { ?>
																		<option value="all" selected="selected"><?php echo $text_subcatdisplay_all; ?></option>
																	<?php } else { ?>
																		<option value="all"><?php echo $text_subcatdisplay_all; ?></option>
																	<?php } ?>
																	<?php if ($item['subcatdisplay'] == 'level1') { ?>
																		<option value="level1" selected="selected"><?php echo $text_subcatdisplay_level1; ?></option>
																	<?php } else { ?>
																		<option value="level1"><?php echo $text_subcatdisplay_level1; ?></option>
																	<?php } ?>
																	<?php if ($item['subcatdisplay'] == 'none') { ?>
																		<option value="none" selected="selected"><?php echo $text_subcatdisplay_none; ?></option>
																	<?php } else { ?>
																		<option value="none"><?php echo $text_subcatdisplay_none; ?></option>
																	<?php } ?>
																</select>
															</td>
														</tr>
														<tr>
															<td><?php echo $text_dwidth; ?></td>
															<td><input size="3" type="text" name="supermenu_item[<?php echo $item_row; ?>][dwidth]" value="<?php echo isset($item['dwidth']) ? $item['dwidth'] : ''; ?>" /></td>
															<td> <?php echo $text_iwidth; ?></td>
															<td><input size="3" type="text" name="supermenu_item[<?php echo $item_row; ?>][iwidth]" value="<?php echo isset($item['iwidth']) ? $item['iwidth'] : ''; ?>" /></td>
														</tr>
														<tr>
															<td><?php echo $text_stores; ?></td>
															<td>
																<div class="scrollbox" style=" width: 180px; height: 60px;">
																	<?php $sbclass = 'even'; ?>
																		<div class="<?php echo $sbclass; ?>">
																			<?php if (isset($item['stores'])) { ?>
																				<?php if (in_array(0, $item['stores'])) { ?>
																					<input type="checkbox" name="supermenu_item[<?php echo $item_row; ?>][stores][]" value="0" checked="checked" />
																					<?php echo $text_default; ?>
																				<?php } else { ?>
																					<input type="checkbox" name="supermenu_item[<?php echo $item_row; ?>][stores][]" value="0" />
																					<?php echo $text_default; ?>
																				<?php } ?>
																			<?php } else { ?>
																				<input type="checkbox" name="supermenu_item[<?php echo $item_row; ?>][stores][]" value="0" />
																				<?php echo $text_default; ?>
																			<?php } ?>
																		</div>
																		<?php foreach ($stores as $store) { ?>
																			<?php $sbclass = ($sbclass == 'even' ? 'odd' : 'even'); ?>
																			<div class="<?php echo $sbclass; ?>">
																				<?php if (isset($item['stores'])) { ?>
																					<?php if (in_array($store['store_id'], $item['stores'])) { ?>
																						<input type="checkbox" name="supermenu_item[<?php echo $item_row; ?>][stores][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
																						<?php echo $store['name']; ?>
																					<?php } else { ?>
																						<input type="checkbox" name="supermenu_item[<?php echo $item_row; ?>][stores][]" value="<?php echo $store['store_id']; ?>" />
																						<?php echo $store['name']; ?>
																					<?php } ?>
																				<?php } else { ?>
																					<input type="checkbox" name="supermenu_item[<?php echo $item_row; ?>][stores][]" value="<?php echo $store['store_id']; ?>" />
																					<?php echo $store['name']; ?>
																				<?php } ?>
																			</div>
																		<?php } ?>
																</div>
															</td>
															<td><?php echo $text_fbrands; ?></td>
															<td><input size="28" type="text" name="supermenu_item[<?php echo $item_row; ?>][fbrands]" value="<?php echo isset($item['fbrands']) ? $item['fbrands'] : ''; ?>" /></td>
														</tr>
													</table>
												</div>
											</div>
											<script type="text/javascript"><!--
												$('.advm<?php echo $item_row; ?> .expand').bind('click', function() {
													$('.advm<?php echo $item_row; ?>').find('.advh').slideToggle('fast');
												});
												$('.advm<?php echo $item_row; ?> .advh .closeadvm').bind('click', function() {
													$('.advm<?php echo $item_row; ?>').find('.advh').slideUp('fast');
												}); 
											//--></script>
										</td>
										<td class="text-left" style="background: <?php echo $libg; ?>;">
											<button type="button" onclick="$('#item-row<?php echo $item_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
										</td>
									</tr>
								</tbody>
								<?php $item_row++; ?>
						<?php } ?>
						<tfoot>
								<tr>
									<td colspan="3"></td>
									<td class="text-left">
										<button type="button" onclick="additem();" data-toggle="tooltip" title="<?php echo $button_add_item; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
									</td>
								</tr>
						</tfoot>
					</table>
				  </div>
				</div>
				<div class="tab-pane" id="tab-settings">
		<div class="row">
		<div class="col-sm-5 col-xs-12" >
		<h2 class="h2settings"><?php echo $text_general; ?></h2>
		<div class="settings-row">
		<?php echo $text_settings_dropdowntitle; ?>
		<?php if ($supermenu_dropdowntitle) { ?>
                <input type="radio" name="supermenu_dropdowntitle" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="supermenu_dropdowntitle" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="supermenu_dropdowntitle" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="supermenu_dropdowntitle" value="0" checked="checked" />
                <?php echo $text_no; ?>
        <?php } ?>		  
		</div>
		<div class="settings-row">
		<?php echo $text_settings_menuskin; ?>
		<select name="supermenu_skin">
			      <?php if ($supermenu_skin == 'default') { ?>
                  <option value="default" selected="selected">Default</option>
                  <?php } else { ?>
                  <option value="default">Default</option>
                  <?php } ?>
				  <?php if ($supermenu_skin == 'white') { ?>
                  <option value="white" selected="selected">White</option>
                  <?php } else { ?>
                  <option value="white">White</option>
                  <?php } ?>
				  <?php if ($supermenu_skin == 'alt1') { ?>
                  <option value="alt1" selected="selected">Alternative 1</option>
                  <?php } else { ?>
                  <option value="alt1">Alternative 1</option>
                  <?php } ?>
				  <?php if ($supermenu_skin == 'alt2') { ?>
                  <option value="alt2" selected="selected">Alternative 2</option>
                  <?php } else { ?>
                  <option value="alt2">Alternative 2</option>
                  <?php } ?>
				  <?php if ($supermenu_skin == 'imgmenu') { ?>
                  <option value="imgmenu" selected="selected">Image Menu</option>
                  <?php } else { ?>
                  <option value="imgmenu">Image Menu</option>
                  <?php } ?>
		</select>		  
		</div>
		<div class="settings-row">
		<?php echo $text_settings_topitemlink; ?>
		<select name="supermenu_topitemlink">
			      <?php if ($supermenu_topitemlink == 'bottom') { ?>
                  <option value="bottom" selected="selected">"View all" button in bottom of dropdown</option>
                  <?php } else { ?>
                  <option value="bottom">"View all" button in bottom of dropdown</option>
                  <?php } ?>
				  <?php if ($supermenu_topitemlink == 'topitem') { ?>
                  <option value="topitem" selected="selected">Top item has link</option>
                  <?php } else { ?>
                  <option value="topitem">Top item has link</option>
                  <?php } ?>
				  <?php if ($supermenu_topitemlink == 'heading') { ?>
                  <option value="heading" selected="selected">The dropdown title has link</option>
                  <?php } else { ?>
                  <option value="heading">The dropdown title has link</option>
                  <?php } ?>
		</select>		  
		</div>
		<div class="settings-row">
		<?php echo $text_settings_tophomelink; ?>
		<select name="supermenu_tophomelink">
			      <?php if ($supermenu_tophomelink == 'none') { ?>
                  <option value="none" selected="selected">No</option>
                  <?php } else { ?>
                  <option value="none">No</option>
                  <?php } ?>
				  <?php if ($supermenu_tophomelink == 'light') { ?>
                  <option value="light" selected="selected">Yes - light icon</option>
                  <?php } else { ?>
                  <option value="light">Yes - light icon</option>
                  <?php } ?>
				  <?php if ($supermenu_tophomelink == 'dark') { ?>
                  <option value="dark" selected="selected">Yes - dark icon</option>
                  <?php } else { ?>
                  <option value="dark">Yes - dark icon</option>
                  <?php } ?>
		</select>		  
		</div>
		<div class="settings-row">
		<?php echo $text_settings_supercache; ?> &nbsp; 
		<?php if ($supermenu_cache) { ?>
                <input type="radio" name="supermenu_cache" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="supermenu_cache" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="supermenu_cache" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="supermenu_cache" value="0" checked="checked" />
                <?php echo $text_no; ?>
        <?php } ?>
		</div>
		<div class="settings-row" style="display: none;">
		<?php echo $text_settings_flyoutwidth; ?>
		<input type="text" name="supermenu_flyout_width" value="<?php echo $supermenu_flyout_width; ?>" size="3" />px
		</div>
		<div class="settings-row">
		<?php echo $text_settings_bspacewidth; ?>
		<input type="text" name="supermenu_bannerspace_width" value="<?php echo $supermenu_bannerspace_width; ?>" size="3" />px
		</div>
		<div class="settings-row">
		<?php echo $text_3dlevellimit; ?>
		<input type="text" name="supermenu_3dlevellimit" value="<?php echo $supermenu_3dlevellimit; ?>" size="3" />
		</div>
		<div class="settings-row">
		<?php echo $entry_image_size; ?> <input type="text" name="supermenu_image_width" value="<?php echo $supermenu_image_width; ?>" size="3" />px <input type="text" name="supermenu_image_height" value="<?php echo $supermenu_image_height; ?>" size="3" />px
		</div>
		<div class="settings-row">
		<?php echo $text_settings_dropeffect; ?>
		<select name="supermenu_dropdowneffect">
			      <?php if ($supermenu_dropdowneffect == 'drop') { ?>
                  <option value="drop" selected="selected">Slide down</option>
                  <?php } else { ?>
                  <option value="drop">Slide down</option>
                  <?php } ?>
				  <?php if ($supermenu_dropdowneffect == 'fade') { ?>
                  <option value="fade" selected="selected">Fade In</option>
                  <?php } else { ?>
                  <option value="fade">Fade In</option>
                  <?php } ?>
				  <?php if ($supermenu_dropdowneffect == 'show') { ?>
                  <option value="show" selected="selected">Just show</option>
                  <?php } else { ?>
                  <option value="show">Just show</option>
                  <?php } ?>
		</select>		  
		</div>
		<div class="settings-row">
		<?php echo $text_settings_hoverintent; ?>
		<?php if ($supermenu_usehoverintent) { ?>
                <input type="radio" name="supermenu_usehoverintent" value="1" checked="checked" />
                <?php echo $text_no; ?>
                <input type="radio" name="supermenu_usehoverintent" value="0" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="supermenu_usehoverintent" value="1" />
                <?php echo $text_no; ?>
                <input type="radio" name="supermenu_usehoverintent" value="0" checked="checked" />
                <?php echo $text_yes; ?>
        <?php } ?>		  
		</div>
		<h2 class="h2settings"><?php echo $text_languagerelated; ?></h2>
		
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $more_name; ?>
	    <input type="text" name="supermenu_more_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_more_title[$language['language_id']]) ? $supermenu_more_title[$language['language_id']] : ''; ?>" />
		</div>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $more2_name; ?>
	    <input type="text" name="supermenu_more2_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_more2_title[$language['language_id']]) ? $supermenu_more2_title[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
		<?php echo $text_settings_mobilemenuname; ?>
	    <input type="text" name="supermenu_mobilemenuname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_mobilemenuname[$language['language_id']]) ? $supermenu_mobilemenuname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
		<?php echo $text_settings_infodname; ?>
	    <input type="text" name="supermenu_infodname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_infodname[$language['language_id']]) ? $supermenu_infodname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
		<?php echo $text_settings_brandsdname; ?>
	    <input type="text" name="supermenu_brandsdname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_brandsdname[$language['language_id']]) ? $supermenu_brandsdname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
		<?php echo $text_settings_latestpname; ?>
	    <input type="text" name="supermenu_latestpname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_latestpname[$language['language_id']]) ? $supermenu_latestpname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
		<?php echo $text_settings_specialpname; ?>
	    <input type="text" name="supermenu_specialpname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_specialpname[$language['language_id']]) ? $supermenu_specialpname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
		<?php echo $text_settings_featuredpname; ?>
	    <input type="text" name="supermenu_featuredpname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_featuredpname[$language['language_id']]) ? $supermenu_featuredpname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
		<?php echo $text_settings_bestpname; ?>
	    <input type="text" name="supermenu_bestpname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_bestpname[$language['language_id']]) ? $supermenu_bestpname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
		<?php echo $text_settings_viewmorename; ?>
	    <input type="text" name="supermenu_viewmorename[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_viewmorename[$language['language_id']]) ? $supermenu_viewmorename[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<?php foreach ($languages as $language) { ?>
		<div class="settings-row">
	    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
		<?php echo $text_settings_viewallname; ?>
	    <input type="text" name="supermenu_viewallname[<?php echo $language['language_id']; ?>]" value="<?php echo isset($supermenu_viewallname[$language['language_id']]) ? $supermenu_viewallname[$language['language_id']] : ''; ?>" />
		</div>
        <?php } ?>
		<h2 class="h2settings"><?php echo $text_settings_fproduct; ?></h2>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-product">Products</label>
            <div class="col-sm-10">
              <input type="text" name="product" value="" placeholder="Type product name" id="input-product" class="form-control" />
              <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($products as $product) { ?>
                <div id="featured-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                  <input type="hidden" name="supermenu_fproduct[]" value="<?php echo $product['product_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	select: function(item) {
		$('input[name=\'product\']').val('');
		
		$('#featured-product' + item['value']).remove();
		
		$('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="supermenu_fproduct[]" value="' + item['value'] + '" /></div>');	
	}
});
	
$('#featured-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
			
		</div>
		<div class="col-sm-7 col-xs-12" >
		<div class="row">
		<div class="col-sm-6 col-xs-12" >
		<h3 class="h3settings"><?php echo $text_more_dropdown; ?></h3>
		 <div class="well well-sm" style="height: 250px; overflow: auto;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $kat) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($kat['category_id'], $supermenu_more)) { ?>
                    <input type="checkbox" name="supermenu_more[]" value="<?php echo $kat['category_id']; ?>" checked="checked" />
                    <?php echo $kat['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="supermenu_more[]" value="<?php echo $kat['category_id']; ?>" />
                    <?php echo $kat['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
        </div>
        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
		</div>
		<div class="col-sm-6 col-xs-12" >
		<h3 class="h3settings"><?php echo $text_more2_dropdown; ?></h3>
		 <div class="well well-sm" style="height: 250px; overflow: auto;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $kat) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($kat['category_id'], $supermenu_more2)) { ?>
                    <input type="checkbox" name="supermenu_more2[]" value="<?php echo $kat['category_id']; ?>" checked="checked" />
                    <?php echo $kat['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="supermenu_more2[]" value="<?php echo $kat['category_id']; ?>" />
                    <?php echo $kat['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
        </div>
        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
		</div>
		</div>
		<br/>
		<h2 class="h2settings"><?php echo $text_customization; ?></h2>
		<div class="settings-row">
		<b>  Apply the customization settings from below? </b> 
		<?php if ($supermenu_settings_status) { ?>
                <input type="radio" name="supermenu_settings_status" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="supermenu_settings_status" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="supermenu_settings_status" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="supermenu_settings_status" value="0" checked="checked" />
                <?php echo $text_no; ?>
        <?php } ?>
	    </div>
		<div class="settings-row">
		Supermenu font family: <span style="color: #06f; font-weight: normal; font-size: 11px;">( if you want to use another font in supermenu put it in here )</span>
		<input type="text" name="supermenu_settings[fontf]" value="<?php echo isset($settings['fontf']) ? $settings['fontf'] : ''; ?>" size="25" /> 
		</div>
		<div class="settings-row">
		Supermenu top items font size: 
		<select name="supermenu_settings[topfont]">
			      <?php if (isset($settings['topfont'])) { ?>
                  <option value="<?php echo $settings['topfont']; ?>" selected="selected"><?php echo $settings['topfont']; ?></option>
                  <?php } ?>
				  <option value="13px">13px</option>
                  <option value="12px">12px</option>
                  <option value="14px">14px</option>
                  <option value="15px">15px</option>
                  <option value="16px">16px</option>
                  <option value="18px">18px</option>
		 </select>	
		</div>
		<div class="settings-row">
		Supermenu dropdown items font size: 
		<select name="supermenu_settings[dropfont]">
			      <?php if (isset($settings['dropfont'])) { ?>
                  <option value="<?php echo $settings['dropfont']; ?>" selected="selected"><?php echo $settings['dropfont']; ?></option>
                  <?php } ?>
				  <option value="12px">12px</option>
                  <option value="13px">13px</option>
                  <option value="14px">14px</option>
                  <option value="15px">15px</option>
                  <option value="16px">16px</option>
                  <option value="18px">18px</option>
		 </select>	
		</div>
		
		<div class="settings-row">
		 Top menu background gradient:
	     <input type="text" name="supermenu_settings[bg]" class="colors" value="<?php echo isset($settings['bg']) ? $settings['bg'] : ''; ?>" size="7" /> to
		 <input type="text" name="supermenu_settings[bg2]" class="colors" value="<?php echo isset($settings['bg2']) ? $settings['bg2'] : ''; ?>" size="7" />
	    </div>
		<div class="settings-row">
		 Top menu border:
		 <select name="supermenu_settings[tmborderpx]">
			      <?php if (isset($settings['tmborderpx'])) { ?>
                  <option value="<?php echo $settings['tmborderpx']; ?>" selected="selected"><?php echo $settings['tmborderpx']; ?></option>
                  <?php } ?>
				  <option value="default">default</option>
                  <option value="1px">1px</option>
                  <option value="2px">2px</option>
                  <option value="3px">3px</option>
                  <option value="4px">4px</option>
                  <option value="5px">5px</option>
		 </select>	
		 <select name="supermenu_settings[tmbordero]">
			      <?php if (isset($settings['tmbordero'])) { ?>
                  <option value="<?php echo $settings['tmbordero']; ?>" selected="selected"><?php echo $settings['tmbordero']; ?></option>
                  <?php } ?>
                  <option value="all-around">all-around</option>
                  <option value="top">top</option>
                  <option value="left">left</option>
                  <option value="right">right</option>
                  <option value="bottom">bottom</option>
		 </select>	
         <select name="supermenu_settings[tmborders]">
			      <?php if (isset($settings['tmborders'])) { ?>
                  <option value="<?php echo $settings['tmborders']; ?>" selected="selected"><?php echo $settings['tmborders']; ?></option>
                  <?php } ?>
                  <option value="solid">solid</option>
                  <option value="dotted">dotted</option>
                  <option value="dashed">dashed</option>
		</select>		
		 <input type="text" name="supermenu_settings[tmborderc]" class="colors" value="<?php echo isset($settings['tmborderc']) ? $settings['tmborderc'] : ''; ?>" size="7" />
	    </div>
		<div class="settings-row">
	     Top menu item color:
	     <input type="text" name="supermenu_settings[tlc]" class="colors" value="<?php echo isset($settings['tlc']) ? $settings['tlc'] : ''; ?>" size="7" />
		  >> on hover >> 
		 <input type="text" name="supermenu_settings[tlch]" class="colors" value="<?php echo isset($settings['tlch']) ? $settings['tlch'] : ''; ?>" size="7" />
		</div> 
		<div class="settings-row">
	     Top menu item text shadow color:
	     <input type="text" name="supermenu_settings[tlcts]" class="colors" value="<?php echo isset($settings['tlcts']) ? $settings['tlcts'] : ''; ?>" size="7" />
		  >> on hover >> 
		 <input type="text" name="supermenu_settings[tlchts]" class="colors" value="<?php echo isset($settings['tlchts']) ? $settings['tlchts'] : ''; ?>" size="7" />
		</div> 
		<div class="settings-row">
	     Top menu item background on hover:
	     <input type="text" name="supermenu_settings[tlb]" class="colors" value="<?php echo isset($settings['tlb']) ? $settings['tlb'] : ''; ?>" size="7" />
		</div> 
	    <div class="settings-row">
		 Dropdown background:
	     <input type="text" name="supermenu_settings[dbg]" class="colors" value="<?php echo isset($settings['dbg']) ? $settings['dbg'] : ''; ?>" size="7" />
		</div>
	    <div class="settings-row">
		 Flyout background:
	     <input type="text" name="supermenu_settings[fybg]" class="colors" value="<?php echo isset($settings['fybg']) ? $settings['fybg'] : ''; ?>" size="7" />
		</div>
		<div class="settings-row">
		 Dropdown  border:
		 <select name="supermenu_settings[slborderpx]">
			      <?php if (isset($settings['slborderpx'])) { ?>
                  <option value="<?php echo $settings['slborderpx']; ?>" selected="selected"><?php echo $settings['slborderpx']; ?></option>
                  <?php } ?>
				  <option value="default">default</option>
                  <option value="1px">1px</option>
                  <option value="2px">2px</option>
                  <option value="3px">3px</option>
                  <option value="4px">4px</option>
                  <option value="5px">5px</option>
		 </select>	
		 <select name="supermenu_settings[slbordero]">
			      <?php if (isset($settings['slbordero'])) { ?>
                  <option value="<?php echo $settings['slbordero']; ?>" selected="selected"><?php echo $settings['slbordero']; ?></option>
                  <?php } ?>
                  <option value="all-around">all-around</option>
                  <option value="top">top</option>
                  <option value="left">left</option>
                  <option value="right">right</option>
                  <option value="bottom">bottom</option>
		 </select>	
         <select name="supermenu_settings[slborders]">
			      <?php if (isset($settings['slborders'])) { ?>
                  <option value="<?php echo $settings['slborders']; ?>" selected="selected"><?php echo $settings['slborders']; ?></option>
                  <?php } ?>
                  <option value="solid">solid</option>
                  <option value="dotted">dotted</option>
                  <option value="dashed">dashed</option>
		</select>		
		 <input type="text" name="supermenu_settings[slborderc]" class="colors" value="<?php echo isset($settings['slborderc']) ? $settings['slborderc'] : ''; ?>" size="7" />
	    </div>
	    <div class="settings-row">
		 Dropdown first level item color:
	     <input type="text" name="supermenu_settings[dic]" class="colors" value="<?php echo isset($settings['dic']) ? $settings['dic'] : ''; ?>" size="7" />
		  >> on hover >> 
	     <input type="text" name="supermenu_settings[dich]" class="colors" value="<?php echo isset($settings['dich']) ? $settings['dich'] : ''; ?>" size="7" />
		</div>
	    <div class="settings-row">
		 Dropdown first level item background color:
	     <input type="text" name="supermenu_settings[dib]" class="colors" value="<?php echo isset($settings['dib']) ? $settings['dib'] : ''; ?>" size="7" />
		  >> on hover >> 
	     <input type="text" name="supermenu_settings[dibh]" class="colors" value="<?php echo isset($settings['dibh']) ? $settings['dibh'] : ''; ?>" size="7" />
		</div>
		<div class="settings-row">
		 Dropdown first level item border:
		 <select name="supermenu_settings[diborderpx]">
			      <?php if (isset($settings['diborderpx'])) { ?>
                  <option value="<?php echo $settings['diborderpx']; ?>" selected="selected"><?php echo $settings['diborderpx']; ?></option>
                  <?php } ?>
				  <option value="default">default</option>
                  <option value="1px">1px</option>
                  <option value="2px">2px</option>
                  <option value="3px">3px</option>
                  <option value="4px">4px</option>
                  <option value="5px">5px</option>
		 </select>	
		 <select name="supermenu_settings[dibordero]">
			      <?php if (isset($settings['dibordero'])) { ?>
                  <option value="<?php echo $settings['dibordero']; ?>" selected="selected"><?php echo $settings['dibordero']; ?></option>
                  <?php } ?>
                  <option value="all-around">all-around</option>
                  <option value="top">top</option>
                  <option value="left">left</option>
                  <option value="right">right</option>
                  <option value="bottom">bottom</option>
		 </select>	
         <select name="supermenu_settings[diborders]">
			      <?php if (isset($settings['diborders'])) { ?>
                  <option value="<?php echo $settings['diborders']; ?>" selected="selected"><?php echo $settings['diborders']; ?></option>
                  <?php } ?>
                  <option value="solid">solid</option>
                  <option value="dotted">dotted</option>
                  <option value="dashed">dashed</option>
		</select>		
		 <input type="text" name="supermenu_settings[diborderc]" class="colors" value="<?php echo isset($settings['diborderc']) ? $settings['diborderc'] : ''; ?>" size="7" />
	    </div>
	    <div class="settings-row">
	     Dropdown - second level item color:
	     <input type="text" name="supermenu_settings[slc]" class="colors" value="<?php echo isset($settings['slc']) ? $settings['slc'] : ''; ?>" size="7" />
		  >> on hover >>
	     <input type="text" name="supermenu_settings[slch]" class="colors" value="<?php echo isset($settings['slch']) ? $settings['slch'] : ''; ?>" size="7" />
		</div> 
	    <div class="settings-row">
	     Dropdown - second level item background color:
	     <input type="text" name="supermenu_settings[slb]" class="colors" value="<?php echo isset($settings['slb']) ? $settings['slb'] : ''; ?>" size="7" />
		  >> on hover >>
	     <input type="text" name="supermenu_settings[slbh]" class="colors" value="<?php echo isset($settings['slbh']) ? $settings['slbh'] : ''; ?>" size="7" />
		</div>  
	    <div class="settings-row">
	     Dropdown with Flyout - Item color:
	     <input type="text" name="supermenu_settings[flyic]" class="colors" value="<?php echo isset($settings['flyic']) ? $settings['flyic'] : ''; ?>" size="7" />
		  >> on hover >>
	     <input type="text" name="supermenu_settings[flyich]" class="colors" value="<?php echo isset($settings['flyich']) ? $settings['flyich'] : ''; ?>" size="7" />
		</div>
	    <div class="settings-row">
	     Dropdown with Flyout - Item border:
		 <select name="supermenu_settings[flyiborderpx]">
			      <?php if (isset($settings['flyiborderpx'])) { ?>
                  <option value="<?php echo $settings['flyiborderpx']; ?>" selected="selected"><?php echo $settings['flyiborderpx']; ?></option>
                  <?php } ?>
				  <option value="default">default</option>
                  <option value="1px">1px</option>
                  <option value="2px">2px</option>
                  <option value="3px">3px</option>
                  <option value="4px">4px</option>
                  <option value="5px">5px</option>
		 </select>	
		 <select name="supermenu_settings[flyibordero]">
			      <?php if (isset($settings['flyibordero'])) { ?>
                  <option value="<?php echo $settings['flyibordero']; ?>" selected="selected"><?php echo $settings['flyibordero']; ?></option>
                  <?php } ?>
                  <option value="all-around">all-around</option>
                  <option value="top">top</option>
                  <option value="left">left</option>
                  <option value="right">right</option>
                  <option value="bottom">bottom</option>
		 </select>	
         <select name="supermenu_settings[flyiborders]">
			      <?php if (isset($settings['flyiborders'])) { ?>
                  <option value="<?php echo $settings['flyiborders']; ?>" selected="selected"><?php echo $settings['flyiborders']; ?></option>
                  <?php } ?>
                  <option value="solid">solid</option>
                  <option value="dotted">dotted</option>
                  <option value="dashed">dashed</option>
		</select>		
		 <input type="text" name="supermenu_settings[flyiborderc]" class="colors" value="<?php echo isset($settings['flyiborderc']) ? $settings['flyiborderc'] : ''; ?>" size="7" />
	    </div>
		<div class="settings-row">
	      Dropdown with Flyout - Item background on hover:
	     <input type="text" name="supermenu_settings[flyib]" class="colors" value="<?php echo isset($settings['flyib']) ? $settings['flyib'] : ''; ?>" size="7" />
		</div> 
		<div class="settings-row">
	      Dropdown title color (if title is displayed):
	     <input type="text" name="supermenu_settings[drtc]" class="colors" value="<?php echo isset($settings['drtc']) ? $settings['drtc'] : ''; ?>" size="7" />
		</div> 
		<div class="settings-row">
	     Dropdown title border:
		 <select name="supermenu_settings[drtborderpx]">
			      <?php if (isset($settings['drtborderpx'])) { ?>
                  <option value="<?php echo $settings['drtborderpx']; ?>" selected="selected"><?php echo $settings['drtborderpx']; ?></option>
                  <?php } ?>
				  <option value="default">default</option>
                  <option value="1px">1px</option>
                  <option value="2px">2px</option>
                  <option value="3px">3px</option>
                  <option value="4px">4px</option>
                  <option value="5px">5px</option>
		 </select>	
		 <select name="supermenu_settings[drtbordero]">
			      <?php if (isset($settings['drtbordero'])) { ?>
                  <option value="<?php echo $settings['drtbordero']; ?>" selected="selected"><?php echo $settings['drtbordero']; ?></option>
                  <?php } ?>
                  <option value="all-around">all-around</option>
                  <option value="top">top</option>
                  <option value="left">left</option>
                  <option value="right">right</option>
                  <option value="bottom">bottom</option>
		 </select>	
         <select name="supermenu_settings[drtborders]">
			      <?php if (isset($settings['drtborders'])) { ?>
                  <option value="<?php echo $settings['drtborders']; ?>" selected="selected"><?php echo $settings['drtborders']; ?></option>
                  <?php } ?>
                  <option value="solid">solid</option>
                  <option value="dotted">dotted</option>
                  <option value="dashed">dashed</option>
		</select>		
		 <input type="text" name="supermenu_settings[drtborderc]" class="colors" value="<?php echo isset($settings['drtborderc']) ? $settings['drtborderc'] : ''; ?>" size="7" />
	    </div>
	    <div class="settings-row">
	     Dropdown - price color:
	     <input type="text" name="supermenu_settings[pricec]" class="colors" value="<?php echo isset($settings['pricec']) ? $settings['pricec'] : ''; ?>" size="7" />
		  >> old price >>
	     <input type="text" name="supermenu_settings[pricech]" class="colors" value="<?php echo isset($settings['pricech']) ? $settings['pricech'] : ''; ?>" size="7" />
		</div>
	    <div class="settings-row">
	     Dropdown - view all link color(if displayed):
	     <input type="text" name="supermenu_settings[valc]" class="colors" value="<?php echo isset($settings['valc']) ? $settings['valc'] : ''; ?>" size="7" />
		  >> on hover >>
	     <input type="text" name="supermenu_settings[valch]" class="colors" value="<?php echo isset($settings['valch']) ? $settings['valch'] : ''; ?>" size="7" />
		</div>
		<div class="settings-row">
		 Dropdown - view all link background gradient:
	     <input type="text" name="supermenu_settings[valb]" class="colors" value="<?php echo isset($settings['valb']) ? $settings['valb'] : ''; ?>" size="7" /> to
		 <input type="text" name="supermenu_settings[valb2]" class="colors" value="<?php echo isset($settings['valb2']) ? $settings['valb2'] : ''; ?>" size="7" />
	    </div>
		<div class="settings-row">
	     Dropdown - view all link border:
		 <select name="supermenu_settings[valborderpx]">
			      <?php if (isset($settings['valborderpx'])) { ?>
                  <option value="<?php echo $settings['valborderpx']; ?>" selected="selected"><?php echo $settings['valborderpx']; ?></option>
                  <?php } ?>
				  <option value="default">default</option>
                  <option value="1px">1px</option>
                  <option value="2px">2px</option>
                  <option value="3px">3px</option>
                  <option value="4px">4px</option>
                  <option value="5px">5px</option>
		 </select>	
		 <select name="supermenu_settings[valbordero]">
			      <?php if (isset($settings['valbordero'])) { ?>
                  <option value="<?php echo $settings['valbordero']; ?>" selected="selected"><?php echo $settings['valbordero']; ?></option>
                  <?php } ?>
                  <option value="all-around">all-around</option>
                  <option value="top">top</option>
                  <option value="left">left</option>
                  <option value="right">right</option>
                  <option value="bottom">bottom</option>
		 </select>	
         <select name="supermenu_settings[valborders]">
			      <?php if (isset($settings['valborders'])) { ?>
                  <option value="<?php echo $settings['valborders']; ?>" selected="selected"><?php echo $settings['valborders']; ?></option>
                  <?php } ?>
                  <option value="solid">solid</option>
                  <option value="dotted">dotted</option>
                  <option value="dashed">dashed</option>
		</select>		
		 <input type="text" name="supermenu_settings[valborderc]" class="colors" value="<?php echo isset($settings['valborderc']) ? $settings['valborderc'] : ''; ?>" size="7" />
	    </div>
		<div class="settings-row">
		 Expand button (mobile) - background:
	     <input type="text" name="supermenu_settings[expbm]" class="colors" value="<?php echo isset($settings['expbm']) ? $settings['expbm'] : ''; ?>" size="7" /> and color: <input type="text" name="supermenu_settings[expbmc]" class="colors" value="<?php echo isset($settings['expbmc']) ? $settings['expbmc'] : ''; ?>" size="7" /> <br />or if expanded
		 background: <input type="text" name="supermenu_settings[expbme]" class="colors" value="<?php echo isset($settings['expbme']) ? $settings['expbme'] : ''; ?>" size="7" /> and color: <input type="text" name="supermenu_settings[expbmec]" class="colors" value="<?php echo isset($settings['expbmec']) ? $settings['expbmec'] : ''; ?>" size="7" />
	    </div>
		</div>
		</div>
	  
				</div>
				<div class="tab-pane" id="tab-html">
					<ul class="nav nav-tabs" id="language">
						<?php foreach ($languages as $language) { ?>
						<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
						<?php } ?>
					</ul>
		 <div class="tab-content">
		 <?php foreach ($languages as $language) { ?>
          <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area1<?php echo $language['language_id']; ?>">Area 1</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea1[<?php echo $language['language_id']; ?>]" id="area1<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea1[$language['language_id']]) ? $supermenu_htmlarea1[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area2<?php echo $language['language_id']; ?>">Area 2</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea2[<?php echo $language['language_id']; ?>]" id="area2<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea2[$language['language_id']]) ? $supermenu_htmlarea2[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area3<?php echo $language['language_id']; ?>">Area 3</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea3[<?php echo $language['language_id']; ?>]" id="area3<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea3[$language['language_id']]) ? $supermenu_htmlarea3[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area4<?php echo $language['language_id']; ?>">Area 4</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea4[<?php echo $language['language_id']; ?>]" id="area4<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea4[$language['language_id']]) ? $supermenu_htmlarea4[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area5<?php echo $language['language_id']; ?>">Area 5</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea5[<?php echo $language['language_id']; ?>]" id="area5<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea5[$language['language_id']]) ? $supermenu_htmlarea5[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area6<?php echo $language['language_id']; ?>">Area 6</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea6[<?php echo $language['language_id']; ?>]" id="area6<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea6[$language['language_id']]) ? $supermenu_htmlarea6[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area7<?php echo $language['language_id']; ?>">Area 7</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea7[<?php echo $language['language_id']; ?>]" id="area7<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea7[$language['language_id']]) ? $supermenu_htmlarea7[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area8<?php echo $language['language_id']; ?>">Area 8</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea8[<?php echo $language['language_id']; ?>]" id="area8<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea8[$language['language_id']]) ? $supermenu_htmlarea8[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area9<?php echo $language['language_id']; ?>">Area 9</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea9[<?php echo $language['language_id']; ?>]" id="area9<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea9[$language['language_id']]) ? $supermenu_htmlarea9[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="area10<?php echo $language['language_id']; ?>">Area 10</label>
                    <div class="col-sm-10">
                      <textarea name="supermenu_htmlarea10[<?php echo $language['language_id']; ?>]" id="area10<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($supermenu_htmlarea10[$language['language_id']]) ? $supermenu_htmlarea10[$language['language_id']] : ''; ?></textarea>
                    </div>
                 </div>
		  </div>
		 <?php } ?> 
		 </div>
		</div>
		</div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var item_row = <?php echo $item_row; ?>;

function additem() {	
	html  = '<tbody id="item-row' + item_row + '">';
	html += '  <tr>';
	html += '    <td class="text-left"><select class="item-type" style="border: 4px solid #333;" id="item-type' + item_row + '" name="supermenu_item[' + item_row + '][type]">';
	html += '      <option class="ocat" id="ocat' + item_row + '" value="cat"><?php echo $type_cat; ?></option>';
	html += '      <option class="oinfol" id="oinfol' + item_row + '" value="infol"><?php echo $type_infol; ?></option>';
	html += '      <option class="infod" value="infod"><?php echo $type_infod; ?></option>';
	html += '      <option class="ocustom" id="ocustom' + item_row + '" value="custom"><?php echo $type_custom; ?></option>';
	html += '      <option class="mand" id="mand' + item_row + '" value="mand"><?php echo $type_mand; ?></option>';
	html += '      <option class="more" id="more' + item_row + '" value="more"><?php echo $type_more; ?></option>';
	html += '      <option class="moredoi" id="moredoi' + item_row + '" value="more2"><?php echo $type_more2; ?></option>';
	html += '      <option class="oproducts" id="oproducts' + item_row + '" value="products"><?php echo $type_products; ?></option>';
	html += '      <option class="catprods" id="catprods' + item_row + '" value="catprods"><?php echo $type_catprods; ?></option>';
	html += '      <option class="login" id="login' + item_row + '" value="login"><?php echo $type_login; ?></option>';
	html += '    </select><div class="viewsel viewsel' + item_row + '"><b><?php echo $text_sview; ?> </b><select name="supermenu_item[' + item_row + '][view]"><option value="0" selected="selected"><?php echo $text_slist; ?></option><option value="1"><?php echo $text_sgrid; ?></option><option value="f0"><?php echo $text_dflist; ?></option><option value="f1"><?php echo $text_dfgrid; ?></option></select></div></td>';
	html += '    <td class="text-left"><div style="position: relative;"><div class="sorder"><?php echo $text_sorder; ?><input size="2" type="text" name="supermenu_item[' + item_row + '][sorder]" value="" /></div><div id="cat-type' + item_row + '" class="typediv cat-type"><h3 class="superh3"><?php echo $entry_category; ?></h3><select style="max-width: 210px;" name="supermenu_item[' + item_row + '][category_id]">';
	<?php foreach ($categories as $category ){ ?>
	html += '      <option value="<?php echo $category['category_id']; ?>"><?php echo str_replace("'", "", $category['name']); ?></option>';
	<?php } ?>       
	html += '    </select></div>';
	html += '     <div id="info-type' + item_row + '" class="typediv info-type"><h3 class="superh3"><?php echo $entry_information; ?></h3><select style="max-width: 210px;" name="supermenu_item[' + item_row + '][information_id]">';
	<?php foreach ($informations as $information ){ ?>
	html += '      <option value="<?php echo $information['information_id']; ?>"><?php echo str_replace("'", "", $information['name']); ?></option>';
	<?php } ?>       
	html += '    </select></div>';
	html += '    <div id="oproducts-type' + item_row + '" class="typediv oproducts-type"> <h3 class="superh3"><?php echo $text_whatproducts; ?></h3><select style="max-width: 210px;" name="supermenu_item[' + item_row + '][products]"><option value="latest"><?php echo $text_productlatest; ?></option><option value="special"><?php echo $text_productspecial; ?></option><option value="featured"><?php echo $text_productfeatured; ?></option><option value="bestseller"><?php echo $text_productbestseller; ?></option></select></div>';
	html += '    <div id="productlimit-type' + item_row + '" class="typediv productlimit-type"><?php echo $text_productlimit; ?> <input size="1" type="text" name="supermenu_item[' + item_row + '][productlimit]" value="5" /></div>';
	html += '    <div id="custom-type' + item_row + '" class="typediv custom-type"> <h3 class="superh3"><?php echo $entry_custom; ?></h3>';
    <?php foreach ($languages as $language) { ?>
	html += '     <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $custom_name; ?> <input type="text" name="supermenu_item[' + item_row + '][customname][<?php echo $language['language_id']; ?>]" value="" /><br /><br /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $custom_url; ?> <input type="text" name="supermenu_item[' + item_row + '][customurl][<?php echo $language['language_id']; ?>]" value="" /><br /><br />';
	<?php } ?>
	html += '    </div> <h3 class="addsuperh3" id="hideadd-type' + item_row + '"><?php echo $entry_add; ?></h3><div id="add-type' + item_row + '" class="addtypediv">';
	<?php foreach ($languages as $language) { ?>
	html +=	'		<div style="padding: 3px; background: #eee; border: 1px solid #ddd; margin-bottom: 2px; text-align: left; font-weight: bold;"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> ';
	html += '     URL: <input type="text" name="supermenu_item[' + item_row + '][addurl][<?php echo $language['language_id']; ?>]" value="" />';
	html +=	'		<div><a href="" id="thumb-image_' + item_row + '<?php echo $language['language_id']; ?>" data-toggle="image" class="img-thumbnail"><img style="max-width: 200px" src="<?php echo $no_image; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a><input type="hidden" name="supermenu_item[' + item_row + '][image][<?php echo $language['language_id']; ?>]" value="" id="input-image_' + item_row + '<?php echo $language['language_id']; ?>" /></div></div>';
	<?php } ?>
	html += '		</div></div></td> ';
	html += '    <td class="text-left"><style type="text/css"> .advm' + item_row + ' .advh { display: none; } </style><div class="advm' + item_row + ' advs"><a class="expand"><?php echo $text_expando; ?></a><div class="advh"><a class="closeadvm"><strong><span style="font-size:20px;"><span style="font-family:lucida sans unicode,lucida grande,sans-serif;">x</span></span></strong></a><table class="smenua"><tr><td><?php echo $text_tlcolor; ?></td><td><input type="text" class="colors" name="supermenu_item[' + item_row + '][tlcolor]" value="" size="7" /></td><td><?php echo $text_tlstyle; ?></td><td><input type="text" name="supermenu_item[' + item_row + '][tlstyle]" value="" size="28" /></td></tr><tr><td><?php echo $text_chtml; ?></td><td><select name="supermenu_item[' + item_row + '][chtml]"><option value="0"><?php echo $text_no; ?></option><option value="1"><?php echo $text_alldrop; ?></option><option value="3"><?php echo $text_overdrop; ?></option><option value="2"><?php echo $text_justadd; ?></option></select><select name="supermenu_item[' + item_row + '][cchtml]"><option value="area1">Area 1</option><option value="area2">Area 2</option><option value="area3">Area 3</option><option value="area4">Area 4</option><option value="area5">Area 5</option><option value="area6">Area 6</option><option value="area7">Area 7</option><option value="area8">Area 8</option><option value="area9">Area 9</option><option value="area10">Area 10</option></select></td><td><?php echo $text_subcatdisplay; ?></td><td><select name="supermenu_item[' + item_row + '][subcatdisplay]"><option value="all"><?php echo $text_subcatdisplay_all; ?></option><option value="level1"><?php echo $text_subcatdisplay_level1; ?></option><option value="none"><?php echo $text_subcatdisplay_none; ?></option></select></td></tr><tr><td><?php echo $text_dwidth; ?></td><td><input type="text" name="supermenu_item[' + item_row + '][dwidth]" value="" size="3" /></td><td><?php echo $text_iwidth; ?></td><td><input type="text" name="supermenu_item[' + item_row + '][iwidth]" value="" size="3" /></td></tr><tr><td><?php echo $text_stores; ?></td><td><div class="scrollbox" style=" width: 180px; height: 60px;"><?php $sbclass = 'even'; ?><div class="<?php echo $sbclass; ?>"><input type="checkbox" name="supermenu_item[' + item_row + '][stores][]" value="0" checked="checked" /><?php echo $text_default; ?></div><?php foreach ($stores as $store) { ?><?php $sbclass = ($sbclass == 'even' ? 'odd' : 'even'); ?><div class="<?php echo $sbclass; ?>"><input type="checkbox" name="supermenu_item[' + item_row + '][stores][]" value="<?php echo $store['store_id']; ?>" /><?php echo $store['name']; ?></div><?php } ?></td><td><?php echo $text_fbrands; ?></td><td><input type="text" name="supermenu_item[' + item_row + '][fbrands]" value="" size="28" /></td></tr></table></div></div>';
	html += '<script type="text/javascript">';
	html += 'superh3stat' + item_row + ' = $(\'#hideadd-type' + item_row + '\').hasClass(\'active\');';
	html += '$(\'#hideadd-type' + item_row + '\').bind(\'click\', function() {';
	html += 'if (!superh3stat' + item_row + ') {';
	html += '$("#add-type' + item_row + '").slideDown(\'fast\');';
	html += '$(this).addClass(\'active\');';
	html += 'superh3stat' + item_row + ' = true;';
	html += '} else {';
	html += '$("#add-type' + item_row + '").slideUp(\'fast\');';
	html += '$(this).removeClass(\'active\');';
	html += 'superh3stat' + item_row + ' = false;';
	html += '} });';
	html += ' $(function() {';
	html += ' $("#item-type' + item_row + '").change(function() {';
	html += ' if ($("#ocat' + item_row + '").is(":selected")) {';
	html += ' $("#cat-type' + item_row + '").slideDown(\'fast\');';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").slideDown(\'fast\');';
	html += ' $("#productlimit-type' + item_row + '").hide();';
	html += ' } else if ($("#oinfol' + item_row + '").is(":selected")) {';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' $("#info-type' + item_row + '").slideDown(\'fast\');';
	html += ' $("#cat-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").hide();';
	html += ' $("#productlimit-type' + item_row + '").hide();';
	html += ' } else if ($("#ocustom' + item_row + '").is(":selected")) {';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#cat-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").slideDown(\'fast\');';
	html += ' $(".viewsel' + item_row + '").hide();';
	html += ' $("#productlimit-type' + item_row + '").hide();';
	html += ' } else if ($("#mand' + item_row + '").is(":selected")) {';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#cat-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").slideDown(\'fast\');';
	html += ' $("#productlimit-type' + item_row + '").hide();';
	html += ' } else if ($("#more' + item_row + '").is(":selected")) {';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#cat-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").slideDown(\'fast\');';
	html += ' $("#productlimit-type' + item_row + '").hide();';
	html += ' } else if ($("#moredoi' + item_row + '").is(":selected")) {';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#cat-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").slideDown(\'fast\');';
	html += ' $("#productlimit-type' + item_row + '").hide();';
	html += ' } else if ($("#oproducts' + item_row + '").is(":selected")) {';
	html += ' $("#oproducts-type' + item_row + '").slideDown(\'fast\');';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#cat-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").slideDown(\'fast\');';
	html += ' $("#productlimit-type' + item_row + '").slideDown(\'fast\');';
	html += ' } else if ($("#catprods' + item_row + '").is(":selected")) {';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#cat-type' + item_row + '").slideDown(\'fast\');';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").slideDown(\'fast\');';
	html += ' $("#productlimit-type' + item_row + '").slideDown(\'fast\');';
	html += ' } else {';
	html += ' $("#info-type' + item_row + '").hide();';
	html += ' $("#cat-type' + item_row + '").hide();';
	html += ' $("#custom-type' + item_row + '").hide();';
	html += ' $(".viewsel' + item_row + '").hide();';
	html += ' $("#productlimit-type' + item_row + '").hide();';
	html += ' $("#oproducts-type' + item_row + '").hide();';
	html += ' }}).trigger(\'change\');';
	html += '  });';
    html += '</script>';
	html += '<script type="text/javascript">';
    html += '$(\'.advm' + item_row + ' .expand\').bind(\'click\', function() {';
	html += '$(\'.advm' + item_row + '\').find(\'.advh\').slideToggle(\'fast\');';
    html += '});';
    html += '$(\'.advm' + item_row + ' .advh .closeadvm\').bind(\'click\', function() {';
	html += '$(\'.advm' + item_row + '\').find(\'.advh\').slideUp(\'fast\');';
    html += '});';
    html += '</script></td>';
	html += '    <td class="text-left"><button type="button" onclick="$(\'#item-row' + item_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button</td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#item tfoot').before(html);
	item_row++;
	startColMan();
}
//--></script> 
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<script type="text/javascript"><!--
$(".item-row > tr > td .item-type").change(function() {
  var parent = $(this).parent().parent();
  if ($(this).find('.ocat').is(":selected")) {
     parent.find('.cat-type').slideDown('fast');
     parent.find('.info-type').hide();
     parent.find('.custom-type').hide();
     parent.find('.viewsel').slideDown('fast');
     parent.find('.oproducts-type').hide();
     parent.find('.productlimit-type').hide();
  } else if ($(this).find('.oinfol').is(":selected")) {
     parent.find('.cat-type').hide();
     parent.find('.info-type').slideDown('fast');
     parent.find('.custom-type').hide();
     parent.find('.viewsel').hide();
     parent.find('.oproducts-type').hide();
     parent.find('.productlimit-type').hide();
  } else if ($(this).find('.ocustom').is(":selected")) {
     parent.find('.cat-type').hide();
     parent.find('.info-type').hide();
     parent.find('.custom-type').slideDown('fast');
     parent.find('.viewsel').hide();
     parent.find('.oproducts-type').hide();
     parent.find('.productlimit-type').hide();
  } else if ($(this).find('.mand').is(":selected") || $(this).find('.more').is(":selected") || $(this).find('.moredoi').is(":selected")) {
     parent.find('.cat-type').hide();
     parent.find('.info-type').hide();
     parent.find('.custom-type').hide();
     parent.find('.viewsel').slideDown('fast');
     parent.find('.oproducts-type').hide();
     parent.find('.productlimit-type').hide();
  } else if ($(this).find('.oproducts').is(":selected")) {
     parent.find('.oproducts-type').slideDown('fast');
     parent.find('.cat-type').hide();
     parent.find('.info-type').hide();
     parent.find('.custom-type').hide();
     parent.find('.viewsel').slideDown('fast');
     parent.find('.productlimit-type').slideDown('fast');
  } else if ($(this).find('.catprods').is(":selected")) {
     parent.find('.oproducts-type').hide();
     parent.find('.cat-type').slideDown('fast');
     parent.find('.info-type').hide();
     parent.find('.custom-type').hide();
     parent.find('.viewsel').slideDown('fast');
     parent.find('.productlimit-type').slideDown('fast');
  } else {
     parent.find('.cat-type').hide();
     parent.find('.info-type').hide();
     parent.find('.custom-type').hide();
     parent.find('.viewsel').hide();
     parent.find('.oproducts-type').hide();
     parent.find('.productlimit-type').hide();
  }
}).trigger('change');
//--></script> 
<style type="text/css">
  table.list td {
    vertical-align: top;
	border-bottom: 5px solid #ddd;
	}
	input[type='text'], input[type='password'] {
	border: 1px solid #bbb;
	border-top: 1px solid #e7e7e7;
	border-left: 1px solid #e7e7e7;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	-khtml-border-radius: 2px;
	border-radius: 2px;
	padding: 5px;
	margin: 3px;
	}
	input[type='text']:focus, input[type='password']:focus {
	-webkit-box-shadow: 0px 0px 3px #07f;
	-moz-box-shadow: 0px 0px 3px #07f;
	box-shadow: 0px 0px 3px #07f;
	}
	select { 
    border: 2px solid #DDDDDD;
    padding: 5px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	-khtml-border-radius: 3px;
	border-radius: 3px;
    }
	h3.superh3 {margin-top: 5px; margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 3px; color: #FF7200;}
	h3.addsuperh3 {margin-top: 5px; margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 3px; color: #FF7200; cursor: pointer; margin-right: 70px;}
	.typediv, .addtypediv { margin-right: 70px; }
    a.button, .list a.button {
    background: #4094D8;
	color: #fff;
	padding: 6px;
	font-weight: bold;
	border: 1px solid #4FB0FA;
	border-right: 1px solid #3E83B7;
	border-bottom: 1px solid #3E83B7;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	-khtml-border-radius: 3px;
	border-radius: 3px;
	}
	a.button:hover, .list a.button:hover {
	background: #333;
	border: 1px solid #444;
	border-right: 1px solid #222;
	border-bottom: 1px solid #222;
	}
	.htabs {
	border-bottom: 3px solid #ddd;
	}
	.viewsel {
	padding: 5px; 
	background: #ddd;
	border-radius: 3px;
	width: 189px;
	margin-top: 8px;
	}
	.viewsel select { width: 120px; }
	.htabs a {
    border-top: 3px solid #ddd;
	border-left: 3px solid #ddd;
	border-right: 3px solid #ddd;
	background: #88C847;
	-webkit-border-radius: 3px 3px 0px 0px;
	-moz-border-radius: 3px 3px 0px 0px;
	-khtml-border-radius: 3px 3px 0px 0px;
	border-radius: 3px 3px 0px 0px;
	padding: 6px 15px 5px;
	color: #fff;
    }	
	.htabs a.selected { 
     background: #FFFFFF;
	 color: #88C847;
     padding-bottom: 8px;
     }
	 a.butonas, .list a.butonas {
    background: #4094D8;
	color: #fff;
	padding: 4px;
	margin-bottom: 2px;
	display: inline-block;
	line-height: 16px;
	padding-left: 5px;
	padding-right: 5px;
	font-weight: bold;
	border: 1px solid #4FB0FA;
	border-right: 1px solid #3E83B7;
	border-bottom: 1px solid #3E83B7;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	-khtml-border-radius: 3px;
	border-radius: 3px;
	text-decoration: none;
	}
	 a.butonas:hover, .list a.butonas:hover {
	 background: #3A81B7;
	}
	 a.butonasc, .list a.butonasc {
    background: #FF2323;
	color: #fff;
	padding: 4px;
	line-height: 16px;
	padding-left: 7px;
	padding-right: 7px;
	display: inline-block;
	font-weight: bold;
	border: 1px solid #FB6262;
	border-right: 1px solid #C11818;
	border-bottom: 1px solid #C11818;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	-khtml-border-radius: 3px;
	border-radius: 3px;
	margin-bottom: 4px;
	text-decoration: none;
	}
	 a.butonasc:hover, .list a.butonasc:hover {
	 background: #EB1D1D;
	}
	table.sets td {
	 padding-top: 5px;
	 padding-bottom: 5px;
	 border-bottom: 2px solid #ddd;
	}
	.box > .content {
	overflow: visible;
	}
	.advs {
	  overflow: visible;
	  width: 100%;
	  height: 58px;
	  position: relative;
	  text-align: center;
	  padding-top: 10px;
	}
	.advs a {
	  text-decoration: none !important;
	  font-weight: bold;
	  color: #007FFF;
	}
	.advs a.expand {
	  padding: 10px;
	background-color: #e9e9e9;
	 /* Safari 4+, Chrome 1-9 */
    background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#eeeeee), to(#e9e9e9));
     /* Safari 5.1+, Mobile Safari, Chrome 10+ */
    background-image: -webkit-linear-gradient(top, #eeeeee, #e9e9e9); 
     /* Firefox 3.6+ */
    background-image: -moz-linear-gradient(top, #eeeeee, #e9e9e9);
     /* IE 10+ */
    background-image: -ms-linear-gradient(top, #eeeeee, #e9e9e9);
     /* Opera 11.10+ */
    background-image: -o-linear-gradient(top, #eeeeee, #e9e9e9);
	  border: 1px solid #fff;
	  border-radius: 4px;
	  box-shadow: 0px 0px 2px #333;
	  display: block;
	}
	.advs a.closeadvm {
	  float: right;
	  margin-top: -20px;
	}
	.advs .advh {
	  position: absolute;
	  z-index: 99;
	  display: block;
	  width: 700px;
	  background: #fff;
	  border: 3px solid #eee;
	  padding: 20px;
	  text-align: left;
	  right: 0px;
	-webkit-border-radius: 7px;
	-moz-border-radius: 7px;
	-khtml-border-radius: 7px;
	border-radius: 7px;
	-webkit-box-shadow: 0px 0px 30px #999;
	-moz-box-shadow: 0px 0px 30px #999;
	box-shadow: 0px 0px 30px #999;
	}
	table.smenua {
	  width: 100%;
	  background: none;
	  border-top: 1px solid #ddd;
	  border-left: 1px solid #ddd;
	}
	table.smenua td {
	  background: #fff !important;
	  padding: 2px;
	  }
	  .addtypediv { display: none; }
    .sorder {
	    position: absolute;
		right: 0px;
		top: 0px;
		width: 50px;
		color: #fff;
		background: #6d6d6d;
		padding: 5px;
		box-radius: 5px;
		box-shadow: 0px 0px 2px #333;
		text-align: center;
		font-weight: bold;
	  }
	  .sorder input { font-size: 15px; color: #333; font-weight: bold; text-align: center; width: 30px; }
	  select.item-type { width: 200px; }
	  .settings-row { 
	    display: block;
        padding: 5px;
        border-bottom: 1px solid #ddd;
	  }
	  .image { display: block; }
	  .advs .advh { display: none; }
	  .advs a.expand, .advs a.closeadvm { cursor: pointer; }
	  table.smenua { border: none; border-collapse: collapse; }
	  table.smenua td { border: 1px solid #ddd; padding: 4px; }
	  .sorder input { padding: 0px !important; }
	  a { cursor: pointer; }
	  .settings-row {
		padding: 7px;
		border-bottom: 1px solid #eee;
		border-top: 1px solid #eee;
		margin-bottom: 3px;
	  }
	  * { white-space: normal !important; }
  </style>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#area1<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area2<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area3<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area4<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area5<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area6<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area7<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area8<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area9<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
$('#area10<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
<?php } ?>
//--></script> 
<?php echo $footer; ?>