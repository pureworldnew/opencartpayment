<?php echo $header; ?><?php echo $column_left; ?><?php /* echo $column_right; */ ?>
<?php /* 
  #file: catalog/view/theme/default/template/product/product_configurable_weight2cols.tpl
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
  #switched: v1.5.4.1 - v1.5.5.1 - v1.5.6
*/ ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
<!-- Start Grouped Product powered by www.fabiom7.com - Italy -->
  <div class="product-info-grouped-product">
    <?php if ($this->config->get('gp_table_position_config') == 'right') { ?>
    <?php if ($thumb || $images) { ?>
    <div class="image-container-grouped-product">
      <?php if ($thumb) { ?>
      <div class="image-grouped-product">
        <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a>
      </div>
      <?php } ?>
      <?php if ($images) { ?>
      <div class="image-additional-grouped-product image-additional-grouped-product-left">
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
    <?php } elseif ($this->config->get('gp_table_position_config') == 'bottom') { ?>
    <div class="image-container-grouped-product">
      <?php if ($thumb) { ?>
      <div class="image-grouped-product">
        <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a>
      </div>
      <?php } ?>
      <?php if ($images) { ?>
      <?php foreach ($images as $image) { ?>
      <div class="image-additional-grouped-product image-additional-grouped-product-right">
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
    <div style="clear:both;"></div>
    <?php } ?>
    
    <div class="grouped-product gp-config">
      <?php if ($error_configuration) { ?>
      <div class="gp-error-txt"><?php echo $error_configuration; ?></div>
      <?php } ?>
      <?php if ($required_fields) { ?>
      <div class="gp-required-txt"><?php echo $text_required_fields; ?></div>
      <?php } ?>
      <?php if ($product_grouped) { ?>
      <form action="<?php echo $action_add_config; ?>" method="post" enctype="multipart/form-data" id="form-config-addtocart">
        <input type="hidden" name="current_set" value="<?php echo $current_set; ?>" />
<table id="gp-table" class="gp-table">
  <?php if ($this->config->get('use_thead_config')) { ?>
  <thead>
    <tr class="gp-thead">
      <?php if ($gp_column_image) { ?>
      <td class="left"><?php echo $gp_column_image; ?></td><td class="left"><?php echo $gp_column_name; ?></td>
      <td class="left"><?php echo $gp_column_image; ?></td><td class="left"><?php echo $gp_column_name; ?></td>
      <?php } else { ?>
      <td class="left"><?php echo $gp_column_name; ?></td><td class="left"><?php echo $gp_column_name; ?></td>
      <?php } ?>
    </tr>
  </thead>
  <?php } ?>
  <tbody>
  <?php foreach ($config_options as $config_opt_key => $ot) { ?>
  
  <?php if ($ot['option_type_switch'] == 'n') { ?>
  <?php foreach ($ot['gp_products'] as $key => $child) { $child_id = $child['product_id']; ?>
  <tr class="gp-tbody gp-tbody-null" data-relazione-box="<?php echo $child_id; ?>">
    <!-- Inizio cella n1 -->
    <?php if ($gp_column_image) { ?>
    <td class="gp-tcell-1"><div style="<?php echo $img_col_w . $img_col_h; ?>">
      <div class="gp-box-img child<?php echo $child_id; ?>">
        <a href="<?php echo $child['image_column_popup']; ?>" title="" class="colorbox"><img src="<?php echo $child['image_column']; ?>" alt="" /></a>
      </div>
    </div></td>
    <?php } ?>
    <!-- Fine cella n1 //-->
    <!-- Inizio cella n2 -->
    <td class="gp-tcell-2">
      <div style="position:relative;">
        <?php if ($ot['option_name']) { ?>
        <div class="gp-box-name child<?php echo $child_id; ?>"><?php if($ot['option_required']){ ?><span class="required">*</span><?php } ?>
          <h2><a href="<?php echo $ot['compare_link']; ?>" class="gp-details" title=""><?php echo $ot['option_name']; ?></a></h2>
        </div>
        <?php } ?>
        <input type="hidden" name="n<?php echo $ot['option_type']; ?>" value="<?php echo $child_id; ?>" />
        <!-- S qty -->
        <div class="child<?php echo $child_id; ?>">
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-price" value="0" class="sum-price" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-taxable" value="0" class="sum-taxable" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-weight" value="0" class="sum-weight" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-quantity" value="0" class="sum-quantity" />
          <?php if ($child['out_of_stock']) { ?>
            <?php if (!$ot['option_hide_qty']) { ?>
            <div class="gp-box-qty-txt"><?php echo $text_qty; ?></div>
            <div class="gp-box-qty">
              <?php if ($ot['option_qty_max']) { ?>
              <select name="" disabled="disabled" title="<?php echo $button_cart_out; ?>"><option value="0">0</option></select>
              <?php } else { ?>
              <input type="text" name="option[<?php echo $child_id; ?>]" disabled="disabled" title="<?php echo $button_cart_out; ?>" value="0" />
              <?php } ?>
            </div>
            <?php } ?>
          <?php } else { /*in stock*/ ?>
            <?php $cqty=$ot['option_qty_min']; foreach($current_configuration as $pid => $qty)if($child_id == $pid){ $cqty=$qty; } ?>
            <?php if ($ot['option_hide_qty']) { ?>
            <input type="hidden" name="option[<?php echo $child_id; ?>]" value="<?php echo $cqty; ?>" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
            <?php } else { ?>
            <div class="gp-box-qty-txt"><?php echo $text_qty; ?></div>
            <div class="gp-box-qty">
              <?php if ($ot['option_qty_max']) { ?>
              <select name="option[<?php echo $child_id; ?>]" data-default-qty="<?php echo $cqty; ?>" class="child-qty">
                <option value="0">0</option>
                <?php for ($qx = $ot['option_qty_min']; $qx <= $ot['option_qty_max']; $qx++) { ?>
                <option value="<?php echo $qx; ?>"<?php if($cqty == $qx){ echo ' selected="selected"'; } ?>><?php echo $qx; ?></option>
                <?php } ?>
              </select>
              <?php } else { ?>
              <input type="text" name="option[<?php echo $child_id; ?>]" value="<?php echo $cqty; ?>" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
              <?php } ?>
            </div>
            <?php } ?>
            <script type="text/javascript"><!--
			$("[name=\"option[<?php echo $child_id; ?>]\"]").on("keyup change",function(){
				var qty=$(this).val();
				$("#sum-<?php echo $ot['option_type']; ?>-price").val(qty * <?php echo $child['price_value']; ?>);
				$("#sum-<?php echo $ot['option_type']; ?>-taxable").val(qty * <?php echo $child['price_value_ex_tax']; ?>);
				$("#sum-<?php echo $ot['option_type']; ?>-weight").val(qty * <?php echo $child['weight']; ?>);
				$("#sum-<?php echo $ot['option_type']; ?>-quantity").val(qty * 1);
			}).trigger("change");
            //--></script>
          <?php } /*out-in stock*/ ?>
          
          <?php if ($ot['option_qty_min'] > 1 && !$ot['option_hide_qty']) { ?>
          <span class="minimum"><?php echo $ot['minimum_text']; ?></span>
          <?php } ?>
        </div>
        <!-- E qty -->
        <!-- S info -->
        <div class="gp-box-info child<?php echo $child_id; ?>" style="white-space:nowrap;">
          <span><?php echo $text_stock; ?></span> <?php echo $child['stock']; ?><br />
          <span><?php echo $text_model; ?></span> <?php echo $child['model']; ?><br />
          <?php if ($child['sku']) { ?>
          <span><?php echo $text_sku; ?></span> <?php echo $child['sku']; ?><br />
          <?php } ?>
          <?php if ($child['manufacturer']) { ?>
          <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $child['manufacturers']; ?>"><?php echo $child['manufacturer']; ?></a><br />
          <?php } ?>
          <?php if ($child['rating']) { ?>
          <span class="rating"><img src="catalog/view/theme/<?php echo $tcg_template; ?>/image/stars-<?php echo $child['rating']; ?>.png" alt="" /></span><br />
          <?php } ?>
        </div>
        <!-- E info -->
        <div class="gp-loader"></div>
      </div>
    </td>
    <!-- Fine cella n2 //-->
  </tr>
  <?php } /*foreach product n*/ ?>
  <?php } elseif ($ot['option_type_switch'] == 's') { ?>
  <tr class="gp-tbody gp-tbody-select">
    <!-- Inizio cella s1 -->
    <?php if ($gp_column_image) { ?>
    <td class="gp-tcell-1"><div style="<?php echo $img_col_w . $img_col_h; ?>">
      <?php foreach($ot['gp_products'] as $key => $child){ $child_id = $child['product_id']; ?>
      <div class="gp-box-img child<?php echo $child_id; ?>">
        <a href="<?php echo $child['image_column_popup']; ?>" title="" class="colorbox"><img src="<?php echo $child['image_column']; ?>" alt="" /></a>
      </div>
      <?php } ?>
    </div></td>
    <?php } ?>
    <!-- Fine cella s1 //-->
    <!-- Inizio cella s2 -->
    <td class="gp-tcell-2"><div style="position:relative;">
        <?php if ($ot['option_name']) { ?>
        <div class="gp-box-name"><?php if($ot['option_required']){ ?><span class="required">*</span><?php } ?>
          <h2><a href="<?php echo $ot['compare_link']; ?>" class="gp-details" title=""><?php echo $ot['option_name']; ?></a></h2>
        </div>
        <?php } ?>
        <div class="gp-box-switch">
          <input type="hidden" id="nds-<?php echo $ot['option_type']; ?>" value="" class="nascondi-prodotti" value="" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-price" value="0" class="sum-price" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-taxable" value="0" class="sum-taxable" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-weight" value="0" class="sum-weight" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-quantity" value="0" class="sum-quantity" />
          <select name="s<?php echo $ot['option_type']; ?>" class="child-switch">
            <?php foreach ($ot['gp_products'] as $key => $child) { $child_id = $child['product_id']; ?>
            <?php if ($child['out_of_stock']) { ?>
            <option value="<?php echo $child_id; ?>" disabled="disabled"><?php echo $child['name']; ?> (<?php echo $button_cart_out; ?>)</option>
            <?php } else { ?>
            <?php $cqty=$ot['option_qty_min']; foreach($current_configuration as $pid => $qty)if($child_id==$pid){ $cqty=$qty; } ?>
            <option value="<?php echo $child_id; ?>" data-price="<?php echo $child['price_value']; ?>" data-taxable="<?php echo $child['price_value_ex_tax']; ?>" data-weight="<?php echo $child['weight']; ?>" data-default-qty="<?php echo $cqty; ?>" data-relazione-hide="<?php echo $child['child_to_hide']; ?>" <?php if(array_key_exists($child_id,$current_configuration)){ echo ' selected="selected"'; } ?>><?php echo $child['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php if ($ot['option_hide_qty'] == 2) { /*add no thanks*/ ?>
            <option value="0" data-price="0" data-taxable="0" data-default-qty="0" data-weight="0" data-relazione-hide="0"><?php echo $text_no_thanks; ?></option>
            <?php } ?>
          </select>
        </div>
        <!-- S qty -->
        <?php if ($ot['option_hide_qty']) { ?>
        <input type="hidden" id="child-qty<?php echo $ot['option_type']; ?>" name="" value="" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
        <?php } else { ?>
        <div class="gp-box-qty-txt"><?php echo $text_qty; ?></div>
        <div class="gp-box-qty">
          <?php if ($ot['option_qty_max']) { ?>
          <select id="child-qty<?php echo $ot['option_type']; ?>" name="" data-default-qty="<?php echo $cqty; ?>" class="child-qty">
            <?php if(!$ot['option_required']){ ?><option value="0">0</option><?php } ?>
            <?php for($qx = $ot['option_qty_min']; $qx <= $ot['option_qty_max']; $qx++){ ?><option value="<?php echo $qx; ?>"><?php echo $qx; ?></option><?php } ?>
          </select>
          <?php } else { ?>
          <input type="text" id="child-qty<?php echo $ot['option_type']; ?>" name="" value="" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($ot['option_qty_min'] > 1 && !$ot['option_hide_qty']) { ?>
        <span class="minimum"><?php echo $ot['minimum_text']; ?></span>
        <?php } ?>
        <!-- E qty -->
        <!-- S info -->
        <?php foreach($ot['gp_products'] as $key => $child){ $child_id = $child['product_id']; ?>
        <div class="gp-box-info child<?php echo $child_id; ?>" style="white-space:nowrap;">
          <span><?php echo $text_stock; ?></span> <?php echo $child['stock']; ?><br />
          <span><?php echo $text_model; ?></span> <?php echo $child['model']; ?><br />
          <?php if ($child['sku']) { ?>
          <span><?php echo $text_sku; ?></span> <?php echo $child['sku']; ?><br />
          <?php } ?>
          <?php if ($child['manufacturer']) { ?>
          <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $child['manufacturers']; ?>"><?php echo $child['manufacturer']; ?></a><br />
          <?php } ?>
          <?php if ($child['rating']) { ?>
          <span class="rating"><img src="catalog/view/theme/<?php echo $tcg_template; ?>/image/stars-<?php echo $child['rating']; ?>.png" alt="" /></span><br />
          <?php } ?>
        </div>
        <?php } ?>
        <!-- E info -->
        <div class="gp-loader"></div>
    </div></td>
    <!-- Fine cella s2 //-->
    <script type="text/javascript"><!--
	$("select[name=\"s<?php echo $ot['option_type']; ?>\"]").on("change",function(){
		$("option",this).each(function(){
			var childID=$(this).val();
			if($(this).is(":selected")){
				var qty=$(this).attr("data-default-qty");
				$("#nds-<?php echo $ot['option_type']; ?>").val($(this).attr("data-relazione-hide"));//Relazioni
				$("#sum-<?php echo $ot['option_type']; ?>-price").val(qty*$(this).attr("data-price"));
				$("#sum-<?php echo $ot['option_type']; ?>-taxable").val(qty*$(this).attr("data-taxable"));
				$("#sum-<?php echo $ot['option_type']; ?>-weight").val(qty*$(this).attr("data-weight"));
				$("#sum-<?php echo $ot['option_type']; ?>-quantity").val(qty*1);
				$(".child"+childID).show();$("#child-qty<?php echo $ot['option_type']; ?>").attr("name","option["+childID+"]").val(qty);
			}else{
				$(".child"+childID).hide();
			}
		});
	}).trigger("change");
	$("#child-qty<?php echo $ot['option_type']; ?>").on("keyup change",function(){
		var qty=$(this).val();var elemento=$("select[name=\"s<?php echo $ot['option_type']; ?>\"] option:selected");
		$("#sum-<?php echo $ot['option_type']; ?>-price").val(qty*elemento.attr("data-price"));
		$("#sum-<?php echo $ot['option_type']; ?>-taxable").val(qty*elemento.attr("data-taxable"));
		$("#sum-<?php echo $ot['option_type']; ?>-weight").val(qty*elemento.attr("data-weight"));
		$("#sum-<?php echo $ot['option_type']; ?>-quantity").val(qty*1);
	});
	//--></script>
  </tr><!-- Select //-->
    
  <?php } elseif ($ot['option_type_switch'] == 'r') { ?>
  <tr class="gp-tbody gp-tbody-radio">
    <!-- Inizio cella r1 -->
    <?php if ($gp_column_image) { ?>
    <td class="gp-tcell-1"><div style="<?php echo $img_col_w . $img_col_h; ?>">
      <?php foreach($ot['gp_products'] as $key => $child){ $child_id = $child['product_id']; ?>
      <div class="gp-box-img child<?php echo $child_id; ?>">
        <a href="<?php echo $child['image_column_popup']; ?>" title="" class="colorbox"><img src="<?php echo $child['image_column']; ?>" alt="" /></a>
      </div>
      <?php } ?>
    </div></td>
    <?php } ?>
    <!-- Fine cella r1 //-->
    <!-- Inizio cella r2 -->
    <td class="gp-tcell-2"><div style="position:relative;">
        <?php if ($ot['option_name']) { ?>
        <div class="gp-box-name"><?php if($ot['option_required']){ ?><span class="required">*</span><?php } ?>
          <h2><a href="<?php echo $ot['compare_link']; ?>" class="gp-details" title=""><?php echo $ot['option_name']; ?></a></h2>
        </div>
        <?php } ?>
        <div class="gp-box-switch">
          <input type="hidden" id="ndr-<?php echo $ot['option_type']; ?>" value="" class="nascondi-prodotti" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-price" value="0" class="sum-price" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-taxable" value="0" class="sum-taxable" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-weight" value="0" class="sum-weight" />
          <input type="hidden" id="sum-<?php echo $ot['option_type']; ?>-quantity" value="0" class="sum-quantity" />
          <?php foreach ($ot['gp_products'] as $key => $child) { $child_id = $child['product_id']; ?>
          <?php if ($child['out_of_stock']) { ?>
          <label data-relazione-box="<?php echo $child_id; ?>" title="<?php echo $button_cart_out; ?>">
            <input type="radio" name="r<?php echo $ot['option_type']; ?>" value="<?php echo $child_id; ?>" disabled="disabled" /> <?php echo $child['name']; ?>
          </label>
          <?php } else { /*in stock*/ ?>
          <?php $cqty=$ot['option_qty_min']; foreach($current_configuration as $pid => $qty)if($child_id==$pid){ $cqty=$qty; } ?>
          <label data-relazione-box="<?php echo $child_id; ?>"><?php $iscc = array_key_exists($child_id,$current_configuration); ?>
            <input type="radio" name="r<?php echo $ot['option_type']; ?>" value="<?php echo $child_id; ?>" data-price="<?php echo $child['price_value']; ?>" data-taxable="<?php echo $child['price_value_ex_tax']; ?>" data-weight="<?php echo $child['weight']; ?>" data-default-qty="<?php echo $cqty; ?>" data-relazione-hide="<?php echo $child['child_to_hide']; ?>" class="child-switch" <?php if($iscc || $key==0 && !$iscc){ echo 'checked="checked"'; } ?> /> <?php echo $child['name']; ?>
          </label>
          <?php } /*out/in stock*/ ?>
          <?php } /*foreach*/ ?>
          <?php if ($ot['option_hide_qty'] == 2) { /*no thanks*/ ?>
          <label> <input type="radio" name="r<?php echo $ot['option_type']; ?>" value="0" data-price="0" data-taxable="0" data-weight="0" data-default-qty="0" data-relazione-hide="0" class="child-switch" /> <?php echo $text_no_thanks; ?></label>
          <?php } ?>
        </div>
        <!-- S qty -->
        <?php if ($ot['option_hide_qty']) { ?>
        <input type="hidden" id="child-qty<?php echo $ot['option_type']; ?>" name="" value="" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
        <?php } else { ?>
        <div class="gp-box-qty-txt"><?php echo $text_qty; ?></div>
        <div class="gp-box-qty">
          <?php if ($ot['option_qty_max']) { ?>
          <select id="child-qty<?php echo $ot['option_type']; ?>" name="" data-default-qty="<?php echo $cqty; ?>" class="child-qty">
            <?php if(!$ot['option_required']){ ?><option value="0">0</option><?php } ?>
            <?php for($qx = $ot['option_qty_min']; $qx <= $ot['option_qty_max']; $qx++){ ?><option value="<?php echo $qx; ?>"><?php echo $qx; ?></option><?php } ?>
          </select>
          <?php } else { ?>
          <input type="text" id="child-qty<?php echo $ot['option_type']; ?>" name="" value="" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($ot['option_qty_min'] > 1 && !$ot['option_hide_qty']) { ?>
        <span class="minimum"><?php echo $ot['minimum_text']; ?></span>
        <?php } ?>
        <!-- E qty -->
		<!-- S info -->
        <?php foreach ($ot['gp_products'] as $key => $child) { $child_id = $child['product_id']; ?>
        <div class="gp-box-info child<?php echo $child_id; ?>" style="white-space:nowrap;">
          <span><?php echo $text_stock; ?></span> <?php echo $child['stock']; ?><br />
          <span><?php echo $text_model; ?></span> <?php echo $child['model']; ?><br />
          <?php if ($child['sku']) { ?>
          <span><?php echo $text_sku; ?></span> <?php echo $child['sku']; ?><br />
          <?php } ?>
          <?php if ($child['manufacturer']) { ?>
          <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $child['manufacturers']; ?>"><?php echo $child['manufacturer']; ?></a><br />
          <?php } ?>
          <?php if ($child['rating']) { ?>
          <span class="rating"><img src="catalog/view/theme/<?php echo $tcg_template; ?>/image/stars-<?php echo $child['rating']; ?>.png" alt="" /></span><br />
          <?php } ?>
        </div>
        <?php } ?>
        <!-- E info -->
        <div class="gp-loader"></div>
    </div></td>
    <!-- Fine cella r2 //-->
    <script type="text/javascript"><!--
	$("input:radio[name=\"r<?php echo $ot['option_type']; ?>\"]").on("change",function(){
		$("input:radio[name=\"r<?php echo $ot['option_type']; ?>\"]").each(function(){
			var childID=$(this).val();
			if($(this).is(":checked")){
				var qty=$(this).attr("data-default-qty");
				$("#ndr-<?php echo $ot['option_type']; ?>").val($(this).attr("data-relazione-hide"));//Relazioni
				$("#sum-<?php echo $ot['option_type']; ?>-price").val(qty*$(this).attr("data-price"));
				$("#sum-<?php echo $ot['option_type']; ?>-taxable").val(qty*$(this).attr("data-taxable"));
				$("#sum-<?php echo $ot['option_type']; ?>-weight").val(qty*$(this).attr("data-weight"));
				$("#sum-<?php echo $ot['option_type']; ?>-quantity").val(qty*1);
				$(".child"+childID).show();$("#child-qty<?php echo $ot['option_type']; ?>").attr("name","option["+childID+"]").val(qty);
			}else{
				$(".child"+childID).hide();
			}
		});
	}).trigger("change");
	$("#child-qty<?php echo $ot['option_type']; ?>").on("keyup change",function(){
		var qty=$(this).val();var elemento=$("input:radio[name=\"r<?php echo $ot['option_type']; ?>\"]:checked");
		$("#sum-<?php echo $ot['option_type']; ?>-price").val(qty*elemento.attr("data-price"));
		$("#sum-<?php echo $ot['option_type']; ?>-taxable").val(qty*elemento.attr("data-taxable"));
		$("#sum-<?php echo $ot['option_type']; ?>-weight").val(qty*elemento.attr("data-weight"));
		$("#sum-<?php echo $ot['option_type']; ?>-quantity").val(qty*1);
	});
	//--></script>
  </tr><!-- Radio //-->
  <?php } elseif ($ot['option_type_switch'] == 'c') { ?>
  <tr class="gp-tbody gp-tbody-checkbox">
    <!-- Inizio cella c1 -->
    <?php if ($gp_column_image) { ?>
    <td class="gp-tcell-1"><div style="<?php echo $img_col_w . $img_col_h; ?>">
      <?php foreach ($ot['gp_products'] as $key => $child) { ?>
      <div class="gp-box-img child<?php echo $child['product_id']; ?>">
        <a href="<?php echo $child['image_column_popup']; ?>" title="" class="colorbox"><img src="<?php echo $child['image_column']; ?>" alt="" /></a>
      </div>
      <?php } ?>
    </div></td>
    <?php } ?>
    <!-- Fine cella c1 //-->
    <!-- Inizio cella c2 -->
    <td class="gp-tcell-2"><div style="position:relative;">
        <?php if ($ot['option_name']) { ?>
        <div class="gp-box-name"><?php if($ot['option_required']){ ?><span class="required">*</span><?php } ?>
          <h2><a href="<?php echo $ot['compare_link']; ?>" class="gp-details" title=""><?php echo $ot['option_name']; ?></a></h2>
        </div>
        <?php } ?>
        <div class="gp-box-switch">
          <?php foreach ($ot['gp_products'] as $key => $child) { $child_id = $child['product_id']; ?>
          <?php $cqty = $ot['option_qty_min']; ?>
          <input type="hidden" id="sum-<?php echo $child_id; ?>-price" value="0" class="sum-price" />
          <input type="hidden" id="sum-<?php echo $child_id; ?>-taxable" value="0" class="sum-taxable" />
          <input type="hidden" id="sum-<?php echo $child_id; ?>-weight" value="0" class="sum-weight" />
          <input type="hidden" id="sum-<?php echo $child_id; ?>-quantity" value="0" class="sum-quantity" />
          <label id="c-l<?php echo $child_id; ?>" data-relazione-box="<?php echo $child_id; ?>">
            <?php if ($child['out_of_stock']) { ?>
            <script type="text/javascript"> $("#c-l<?php echo $child_id; ?>").attr("title","<?php echo $button_cart_out; ?>"); </script>
            <input type="checkbox" name="option[<?php echo $child_id; ?>]" value="0" disabled="disabled" />
            <?php } else { ?>
            <?php if (array_key_exists($child_id,$current_configuration)) { ?>
            <input type="checkbox" name="option[<?php echo $child_id; ?>]" value="<?php echo $cqty; ?>" data-default-qty="<?php echo $cqty; ?>" class="child-qty" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="option[<?php echo $child_id; ?>]" value="<?php echo $cqty; ?>" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
            <?php } ?>
            <?php } ?>
            
            <?php if(!$ot['option_hide_qty']){ echo $ot['option_qty_min'] . 'x'; } ?>
            <?php echo $child['name']; ?>
          </label>
		  <script type="text/javascript"><!--
			<?php if (count($ot['gp_products']) > 1) { ?>
			$(".child<?php echo $child_id; ?>").hide();
			$("#c-l<?php echo $child_id; ?>").mouseover(function(){
				$(".child<?php echo $child_id; ?>").show();
			}).mouseout(function(){
				$(".child<?php echo $child_id; ?>").hide();
			});
			<?php } ?>
			$("input[name=\"option[<?php echo $child_id; ?>]\"]").change(function(){
				if($(this).is(":checked")){
					var qty=$(this).val();
					$("#sum-<?php echo $child_id; ?>-price").val(qty * <?php echo $child['price_value']; ?>);
					$("#sum-<?php echo $child_id; ?>-taxable").val(qty * <?php echo $child['price_value_ex_tax']; ?>);
					$("#sum-<?php echo $child_id; ?>-weight").val(qty * <?php echo $child['weight']; ?>);
					$("#sum-<?php echo $child_id; ?>-quantity").val(qty * 1);
				}else{
					$("#sum-<?php echo $child_id; ?>-price").val(0);
					$("#sum-<?php echo $child_id; ?>-taxable").val(0);
					$("#sum-<?php echo $child_id; ?>-weight").val(0);
					$("#sum-<?php echo $child_id; ?>-quantity").val(0);
				}
			}).trigger('change');
		  //--></script>
		  <?php } ?>
        </div>
        <div class="gp-loader"></div>
    </div></td>
    <!-- Fine cella c2 //-->
  </tr>

  <?php } elseif ($ot['option_type_switch'] == 'i') { ?>
  <tr class="gp-tbody gp-tbody-inline">
    <!-- Inizio cella i1 -->
    <?php if ($gp_column_image) { ?>
    <td class="gp-tcell-1"><div style="<?php echo $img_col_w . $img_col_h; ?>">
      <?php foreach ($ot['gp_products'] as $key => $child) { ?>
      <div class="gp-box-img child<?php echo $child['product_id']; ?>">
        <a href="<?php echo $child['image_column_popup']; ?>" title="" class="colorbox"><img src="<?php echo $child['image_column']; ?>" alt="" /></a>
      </div>
      <?php } ?>
    </div></td>
    <?php } ?>
    <!-- Fine cella i1 //-->
    <!-- Inizio cella i2 -->
    <td class="gp-tcell-2" colspan="2"><div style="position:relative;">
        <?php if ($ot['option_name']) { ?>
        <div class="gp-box-name"><?php if($ot['option_required']){ ?><span class="required">*</span><?php } ?>
          <h2><a href="<?php echo $ot['compare_link']; ?>" class="gp-details" title=""><?php echo $ot['option_name']; ?></a></h2>
        </div>
        <?php } ?>
        <div class="gp-box-switch">
          <?php foreach ($ot['gp_products'] as $key => $child) { $child_id = $child['product_id']; ?>
          <?php $cqty=$ot['option_qty_min']; foreach($current_configuration as $pid => $qty)if($child_id==$pid){ $cqty=$qty; } ?>
          <input type="hidden" id="sum-<?php echo $child_id; ?>-price" value="0" class="sum-price" />
          <input type="hidden" id="sum-<?php echo $child_id; ?>-taxable" value="0" class="sum-taxable" />
          <input type="hidden" id="sum-<?php echo $child_id; ?>-weight" value="0" class="sum-weight" />
          <input type="hidden" id="sum-<?php echo $child_id; ?>-quantity" value="0" class="sum-quantity" />
          <label id="i-l<?php echo $child_id; ?>" data-relazione-box="<?php echo $child_id; ?>">
            <?php if ($child['out_of_stock']) { ?>
            <script type="text/javascript"> $("#i-l<?php echo $child_id; ?>").attr("title","<?php echo $button_cart_out; ?>"); </script>
            <?php if (!$ot['option_hide_qty']) { ?>
            <div class="gp-box-qty-txt" style="margin:0;padding:0;"><?php echo $text_qty; ?></div>
            <div class="gp-box-qty">
              <?php if ($ot['option_qty_max']) { ?>
              <select name="" disabled="disabled"><option value="0">0</option></select>
              <?php } else { ?>
              <input type="text" name="" value="0" disabled="disabled" />
              <?php } ?>
            </div>
            <?php } ?>
            
            <?php } else { /*product in stock*/ ?>
            <?php if ($ot['option_hide_qty']) { ?>
            <input type="hidden" name="option[<?php echo $child_id; ?>]" value="<?php echo $cqty; ?>" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
            <?php } else { ?>
            <div class="gp-box-qty-txt" style="margin:0;padding:0;"><?php echo $text_qty; ?></div>
            <div class="gp-box-qty">
              <?php if ($ot['option_qty_max']) { ?>
              <select name="option[<?php echo $child_id; ?>]" data-default-qty="<?php echo $cqty; ?>" class="child-qty"><option value="0">0</option>
			    <?php for ($qx = $ot['option_qty_min']; $qx <= $ot['option_qty_max']; $qx++) { ?>
                <option value="<?php echo $qx; ?>"<?php if($cqty == $qx){ echo ' selected="selected"'; } ?>><?php echo $qx; ?></option>
			    <?php } ?>
              </select>
              <?php } else { ?>
              <input type="text" name="option[<?php echo $child_id; ?>]" value="<?php echo $cqty; ?>" data-default-qty="<?php echo $cqty; ?>" class="child-qty" />
              <?php } ?>
            </div>
            <?php } ?>
            <?php } /*product in/out stock*/?>
            
            <?php echo $child['name']; ?>
            <?php if ($ot['option_qty_min'] > 1 && !$ot['option_hide_qty']) { ?>
            <span class="minimum"><?php echo $ot['minimum_text']; ?></span>
            <?php } ?>
          </label>
		  <script type="text/javascript"><!--
			<?php if (count($ot['gp_products']) > 1) { ?>
			$(".child<?php echo $child_id; ?>").hide();
			$("#i-l<?php echo $child_id; ?>").mouseover(function(){
				$(".child<?php echo $child_id; ?>").show();
			}).mouseout(function(){
				$(".child<?php echo $child_id; ?>").hide();
			});
			<?php } ?>
			$("[name=\"option[<?php echo $child_id; ?>]\"]").on("keyup change",function(){
				var qty=$(this).val();
				$("#sum-<?php echo $child_id; ?>-price").val(qty * <?php echo $child['price_value']; ?>);
				$("#sum-<?php echo $child_id; ?>-taxable").val(qty * <?php echo $child['price_value_ex_tax']; ?>);
				$("#sum-<?php echo $child_id; ?>-weight").val(qty * <?php echo $child['weight']; ?>);
				$("#sum-<?php echo $child_id; ?>-quantity").val(qty * 1);
			}).trigger("keyup");
		  //--></script>
		  <?php } ?>
        </div>
        <div class="gp-loader"></div>
    </div></td>
    <!-- Fine cella i2 //-->
  </tr>
  <?php } /*option type*/ ?>
  <?php } /*foreach config option*/ ?>
  </tbody>
</table>
        <!-- S after table -->
        <div class="gp-after-t">
          <input type="hidden" name="weight_sum" />
          <div class="gp-progressbar" id="gp-progressbar">
            <div class="progress-label"></div>
            <div class="gp-progress-label-info-empty"><?php echo $this->language->get('text_progressbar_info_empty'); ?></div>
            <div class="gp-progress-label-info-full"><?php echo $this->language->get('text_progressbar_info_full'); ?></div>
          </div>
          <?php if ($gp_discount) { ?>
          <div class="gp-discount-config"><?php echo $gp_discount; ?></div>
          <?php } ?>
          <?php if (!$price_fixed) { ?>
          <div class="price">
            <?php echo $text_price_as_configured; ?> <span id="price_as_configured"></span><br />
            <?php if ($tax) { ?>
            <span class="price-tax"><?php echo $text_tax; ?> <span id="price_as_configured_taxable"></span></span><br />
            <?php } ?>
          </div>
          <?php } else { ?>
          <div class="price">
            <?php echo $text_price; ?> <span><?php echo $price_fixed; ?></span><br />
            <?php if ($tax) { ?>
            <span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span><br />
            <?php } ?>
          </div>
          <?php } ?>
          <div class="gp-actions">
            <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" />
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
            &nbsp;
            <input type="button" value="<?php echo $button_cart; ?>" onClick="$('#form-config-addtocart').submit();" class="button" />
            <span class="gp-after-t-text-or"><?php echo $text_or; ?></span>
            <span class="gp-after-t-links"><a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a><br />
              <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a></span>
          </div>
        </div>
        <!-- E after table -->
        
        <?php if ($review_status) { ?>
        <div class="review">
          <div><img src="catalog/view/theme/<?php echo $tcg_template; ?>/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
          <div class="share"><!-- AddThis Button BEGIN -->
            <div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a> <a class="addthis_button_email"></a><a class="addthis_button_print"></a> <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a></div>
            <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script> 
            <!-- AddThis Button END --> 
          </div>
        </div>
        <?php } ?>
      </form>
      <?php } else { ?>
      <div style="text-align:center;">No Products found!</div>
      <?php } ?>
    </div>
    <div style="clear:both;"></div>
  </div><!-- grouped-product-product-info //-->
<!-- End Grouped Product powered by www.fabiom7.com - Italy //-->
<!--
  <div class="product-info">
    <div class="right">
    </div>
  </div>
//-->
  <div id="tabs" class="htabs"><a href="#tab-description"><?php echo $tab_description; ?></a>
    <?php if ($attribute_groups) { ?>
    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
    <?php } ?>
    <?php if ($review_status) { ?>
    <a href="#tab-review"><?php echo $tab_review; ?></a>
    <?php } ?>
    <?php if ($products) { ?>
    <a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a>
    <?php } ?>
  </div>
  <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
  <?php if ($attribute_groups) { ?>
  <div id="tab-attribute" class="tab-content">
    <table class="attribute">
      <?php foreach ($attribute_groups as $attribute_group) { ?>
      <thead>
        <tr>
          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
  <?php if ($review_status) { ?>
  <div id="tab-review" class="tab-content">
    <div id="review"></div>
    <h2 id="review-title"><?php echo $text_write; ?></h2>
<!-- Start Grouped Product powered by www.fabiom7.com //-->
    <?php if ($product_grouped && $this->config->get('use_individual_review')) { ?>
    <b><?php echo $text_review_for; ?></b><br />
    <select id="switch_review_for">
      <option value="0"><?php echo $text_review_all; ?></option>
      <?php foreach ($product_grouped as $child) { ?>
      <option value="<?php echo $child['product_id'] ?>"><?php echo $child['name']; ?></option>
      <?php } ?>
    </select><br /><br />
    <?php } else { ?>
    <input type="hidden" id="switch_review_for" value="0" />
    <?php } ?>
<!-- End Grouped Product powered by www.fabiom7.com //-->
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_review; ?></b>
    <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp;<span><?php echo $entry_good; ?></span><br />
    <br />
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="" />
    <br />
    <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
    <br />
    <div class="buttons">
      <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($products) { ?>
  <div id="tab-related" class="tab-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <a onClick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><?php echo $button_cart; ?></a></div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>
  <?php if ($tags) { ?>
  <div class="tags"><b><?php echo $text_tags; ?></b>
    <?php for ($i = 0; $i < count($tags); $i++) { ?>
    <?php if ($i < (count($tags) - 1)) { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
    <?php } else { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
//--></script> 

<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload("#button-option-<?php echo $option['product_option_id']; ?>", {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$("#button-option-<?php echo $option['product_option_id']; ?>").after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$("#button-option-<?php echo $option['product_option_id']; ?>").attr('disabled', true);
	},
	onComplete: function(file, json) {
		$("#button-option-<?php echo $option['product_option_id']; ?>").attr('disabled', false);
		$('.error').remove();
		if (json['success']) {
			alert(json['success']);
			$("input[name=\"option[<?php echo $option['product_option_id']; ?>]\"]").attr('value', json['file']);
		}
		if (json['error']) {
			$("#option-<?php echo $option['product_option_id']; ?>").after('<span class="error">' + json['error'] + '</span>');
		}
		$('.loading').remove();	
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');
	$('#review').load(this.href);
	$('#review').fadeIn('slow');
	return false;
});
$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

// Grouped Product new function
$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product_configurable/write&product_id=<?php echo $product_id; ?>&grouped_id='+$('#switch_review_for').val(),
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}
			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	if ($.browser.msie && $.browser.version == 6) {
		$('.date, .datetime, .time').bgIframe();
	}
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').timepicker({timeFormat: 'h:m'});
});
//--></script>

<!-- Grouped Product New script //-->
<script type="text/javascript"><!--
$(document).ready(function(){
	$("#gp-table tbody tr").addClass("gp-tcell-state-default").on({
		mouseover:function(){$(this).addClass("gp-tcell-state-hover").removeClass("gp-tcell-state-normal");},
		mouseout:function(){$(this).removeClass("gp-tcell-state-hover").addClass("gp-tcell-state-normal");}
	});
	$(".gp-details").colorbox({overlayClose:true,opacity:0.5,rel:'gp-details'});
	
	//toggle of gp-box-info with gp-box-info-mini
	<?php if ($gpbisec = $this->config->get('use_child_toggle_info_config')) { ?>
	gpbi=$(".gp-box-info");gpbi.each(function(){$(this).addClass("gp-box-info-minihover");setTimeout(function(){gpbi.addClass("gp-box-info-mini").removeClass("gp-box-info-minihover");},"<?php echo $gpbisec;?>000");gpbi.mouseover(function(){$(this).removeClass("gp-box-info-mini").addClass("gp-box-info-minihover");}).mouseout(function(){$(this).addClass("gp-box-info-mini").removeClass("gp-box-info-minihover");});});
	<?php } ?>
});
//--></script>
<script type="text/javascript"><!--
// Relazioni:
$(".child-switch").on("change",function(){
	var hideChilds='';$("input.nascondi-prodotti").each(function(){if($(this).val()){hideChilds+=','+$(this).val();}});
	var nascondiAll=hideChilds.replace(',','');var nascondi=$.unique(nascondiAll.split(','));
	$(".child-qty:hidden").each(function(){$(this).val($(this).attr("data-default-qty"));});
	$("tr.gp-tbody-checkbox:hidden,tr.gp-tbody-inline:hidden,[data-relazione-box]:hidden").show();//Se tr era vuota e nascosta la visualizzo,e poi data-relazione-box
	$.each(nascondi,function(arr,cid){$('[data-relazione-box="'+cid+'"]').hide();$('[name="option['+cid+']"]').val(0);});
	//Nascondo intera riga se vuota
	$("tr.gp-tbody-checkbox,tr.gp-tbody-inline").each(function(){elemento=$(this).find("label[data-relazione-box]:visible");
		if(elemento.length==0){$(this).hide();}else if(elemento.length>1){$(".child"+elemento.attr("data-relazione-box")).hide();}
	});
	//Seleziono il primo radio disponibile se ne viene nascosto uno gia selezionato
	$("tr.gp-tbody-radio .gp-box-switch").each(function(){if(!$('input[type="radio"]:visible:checked').val()){
		$('input[type="radio"]:visible').each(function(){if(!$(this).is(':disabled')){$(this).attr("checked",true).change();return false;}});
	}});	
	$(".child-qty").change();
}).trigger("change");
$(".child-qty").on("keyup change",function(){
	//var sumTotalQuantity=0; $('.sum-quantity').each(function(){sumTotalQuantity+=1*$(this).val();});$('input[name="sum_total_quantity"]').val(sumTotalQuantity);
	var sumTotalPrice=0;$(".sum-price").each(function(){sumTotalPrice+=1*$(this).val();});//.each(function(i,n){sumTotalPrice+=1*$(n).val();});
	var sumTotalTaxable=0;$(".sum-taxable").each(function(){sumTotalTaxable+=1*$(this).val();});
	$.ajax({
		url:'index.php?route=product/product_configurable/updateSumPrice',type:'post',dataType:'json',
		data:{"bundle_price_sum":sumTotalPrice,"bundle_price_sum_ex_tax":sumTotalTaxable},
		beforeSend:function(){$(".grouped-product .gp-loader").show();},
		complete:function(){$(".grouped-product .gp-loader").hide();},
		success:function(json){
			if(json['text_sum_price']){$("#price_as_configured").html(json['text_sum_price']);}
			if(json['text_sum_price_ex_tax']){$("#price_as_configured_taxable").html(json['text_sum_price_ex_tax']);}
		}
	});

	// Progressbar
	var sumTotalWeight=0;$('.sum-weight').each(function(){sumTotalWeight+=1*$(this).val();});$('input[name="weight_sum"]').val(sumTotalWeight);
	weightProduct = '<?php echo $weight; ?>';
	var valueCurrent = Math.floor(100 / (weightProduct / sumTotalWeight));
	valueMin = "<?php echo 100 - $this->config->get('weight_allow_config_min'); ?>";
	valueMax = "<?php echo 100 + $this->config->get('weight_allow_config_max'); ?>";
	labelEmpty = "<?php echo $this->language->get('text_progressbar_empty'); ?>";
	labelCurrent = "<?php echo $this->language->get('text_progressbar_current'); ?>";
	labelComplete = "<?php echo $this->language->get('text_progressbar_complete'); ?>";
	labelOverload = "<?php echo sprintf($this->language->get('text_progressbar_overload'), $this->config->get('weight_allow_config_max') . '%'); ?>";
	$("#gp-progressbar").progressbar({value:false});
	progressbar = $("#gp-progressbar"),
	progressbarValue = progressbar.find(".ui-progressbar-value");
	progressbar.progressbar("option",{value:valueCurrent});
	progressLabel = $(".progress-label");
	if(valueCurrent == 0){
		progressLabel.html(labelEmpty);
	}else if(valueCurrent < valueMin){
		progressLabel.html(labelCurrent.replace('{value}', valueCurrent + "%"));
		progressbarValue.css("background","#ff9900");
	}else if(valueCurrent >= valueMin && valueCurrent <= valueMax){
		progressLabel.html(labelComplete.replace('{value}', valueCurrent + "%"));
		progressbarValue.css("background","#009900");
	}else if(valueCurrent > valueMax){
		progressLabel.html(labelOverload.replace('{value}', (valueCurrent - valueMax) + "%"));
		progressbarValue.css("background","#ff0000");
	}
}).trigger("change");
//--></script>
<?php echo $footer; ?>