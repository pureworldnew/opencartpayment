<div id="promoteproducts" class="tab-pane" style="width:99%;overflow:hidden;">
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Show products on the success page:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Gives you the ability to show products on the order success page.</span>
      </div>
      <div class="col-md-4">
        <select name="<?php echo $moduleName; ?>[ShowProducts]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['ShowProducts']) && $moduleData['ShowProducts'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['ShowProducts']) || $moduleData['ShowProducts']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Section title:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Write the desired section title here.</span>
      </div>
      <div class="col-md-4">
        <ul class="nav nav-tabs moduledata_promotedproducts_tabs" style="margin-bottom:0px;">
            <?php $i=0; foreach ($languages as $language) { ?>
                <li <?php if ($i==0) echo 'class="active"'; ?>><a href="#promotedtab-<?php echo $i; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>"/> <?php echo $language['name']; ?></a></li>
            <?php $i++; }?>
        </ul>
        <div class="tab-content">
            <?php $i=0; foreach ($languages as $language) { ?>
                <div id="promotedtab-<?php echo $i; ?>-<?php echo $language['language_id']; ?>" language-id="<?php echo $language['language_id']; ?>" class="row-fluid tab-pane language <?php if ($i==0) echo 'active'; ?>">
                        <input placeholder="Page title" type="text" class="form-control" name="<?php echo $moduleName; ?>[PromotedTitle][<?php echo $language['language_id']; ?>]" value="<?php if(!empty($moduleData['PromotedTitle'][$language['language_id']])) echo $moduleData['PromotedTitle'][$language['language_id']]; else echo "You may also want to check out these products:"; ?>" />
				</div> 
            <?php $i++; } ?>
      	</div>
      </div>
    </div>
    <hr />
	<div class="row">
      <div class="col-md-3">
        <h5><strong>Products:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose which products to appear on the page (autocomplete input).</span>
      </div>
      <div class="col-md-4">
        <input type="text" name="products" value="" class="form-control" />
        <div id="product" class="well well-sm" style="height:140px;overflow: auto;">
            <?php if (!empty($moduleData['PromotedProducts'])) {
                foreach ($moduleData['PromotedProducts'] as $pr) { 
                    $product = $modelCatalogProduct->getProduct($pr); ?>
                    <div id="products-<?php echo $pr; ?>"> 
                        <?php echo $product['name']; ?>&nbsp;<i class="fa fa-minus-circle"></i>
                        <input type="hidden" name="<?php echo $moduleName; ?>[PromotedProducts][]" value="<?php echo $pr; ?>" />
                    </div>
                <?php }
            } ?>
        </div>      
      </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-3">
        	<h5><strong>Product images dimensions:</strong></h5>
        	<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Specify here the image width & height.</span>
      	</div>
    	<div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">Width:&nbsp;</span>
                <input type="text" name="<?php echo $moduleName; ?>[PromotedPictureWidth]" class="form-control" value="<?php echo (isset($moduleData['PromotedPictureWidth'])) ? $moduleData['PromotedPictureWidth'] : '150' ?>" />
              <span class="input-group-addon">px</span>
            </div>
            <br />
            <div class="input-group">
              <span class="input-group-addon">Height:</span>
                <input type="text" name="<?php echo $moduleName; ?>[PromotedPictureHeight]" class="form-control" value="<?php echo (isset($moduleData['PromotedPictureHeight'])) ? $moduleData['PromotedPictureHeight'] : '190' ?>" />
              <span class="input-group-addon">px</span>
            </div>
		</div>
    </div>
    <hr />
	<script>
    $('input[name=\'products\']').autocomplete({
        delay: 500,
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
            $('input[name=\'products\']').val('');
            
            $('#product' + item['value']).remove();
            
            $('#product').append('<div id="product' + item['value'] + '">' + item['label'] + '&nbsp;<i class="fa fa-minus-circle"></i><input type="hidden" name="<?php echo $moduleName; ?>[PromotedProducts][]" value="' + item['value'] + '" /></div>');
    
        }
    });
    
    $('#product').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
    </script>
</div>