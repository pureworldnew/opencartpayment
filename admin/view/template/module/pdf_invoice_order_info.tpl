<?php if ($gkd_snippet == 'top_buttons') { ?>
 <a href="<?php echo $link_pdfinv_invoice; ?>" <?php echo !empty($pdf_invoice_new_tab) ? 'target="_blank"':''; ?> data-toggle="tooltip" title="<?php echo $button_pdfinv_invoice; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a> <a href="<?php echo $link_pdfinv_packing; ?>" <?php echo !empty($pdf_invoice_new_tab) ? 'target="_blank"':''; ?> data-toggle="tooltip" title="<?php echo $button_pdfinv_packing; ?>" class="btn btn-warning"><i class="fa fa-file-text-o"></i></a> <?php if($pdf_invoice_manual_backup) { ?><a href="<?php echo $link_pdfinv_backup; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_backup; ?>" class="btn btn-warning"><i class="fa fa-toggle-down"></i></a><?php } ?>
<?php } else if ($gkd_snippet == 'list_buttons') { ?>
  <?php if (version_compare(VERSION, '3', '>=')) { ?>
    <li><a href="<?php echo $link_pdfinv_invoice.'&order_id='.$order['order_id']; ?>" <?php echo !empty($pdf_invoice_new_tab) ? 'target="_blank"':''; ?>><i class="fa fa-file-pdf-o"></i> <?php echo $button_pdfinv_invoice; ?></a></li>
    <li><a href="<?php echo $link_pdfinv_packing.'&order_id='.$order['order_id']; ?>" <?php echo !empty($pdf_invoice_new_tab) ? 'target="_blank"':''; ?>><i class="fa fa-file-text-o"></i> <?php echo $button_pdfinv_packing; ?></a></li>
  <?php } else if (version_compare(VERSION, '2', '>=')) { ?>
    <a href="<?php echo $link_pdfinv_invoice.'&order_id='.$order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_pdfinv_invoice; ?>" class="btn btn-warning" <?php echo !empty($pdf_invoice_new_tab) ? 'target="_blank"':''; ?>><i class="fa fa-file-pdf-o"></i></a>
    <a href="<?php echo $link_pdfinv_packing.'&order_id='.$order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_pdfinv_packing; ?>" class="btn btn-warning" <?php echo !empty($pdf_invoice_new_tab) ? 'target="_blank"':''; ?>><i class="fa fa-file-text-o"></i></a>
  <?php } else { ?>
  <?php } ?>
<?php } else if ($gkd_snippet == 'snippet_metas') { ?>
  <?php if (version_compare(VERSION, '2.2', '>=') || $_config->get('mlseo_enabled')) { ?>
    <div class="form-group">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-10">
        <btn class="btn btn-block btn-default btnSeoGen" onClick="seoPackageGen('all', '<?php echo $_language_id; ?>', '')"><i class="fa fa-bolt"></i> Generate all SEO values</btn>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-seo-keyword<?php echo $_language_id; ?>"><!--span data-toggle="tooltip" title="<?php echo $_language->get('help_keyword'); ?>"--><?php echo $_language->get('entry_keyword'); ?></label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" name="product_description[<?php echo $_language_id; ?>][seo_keyword]" value="<?php echo isset($product_description[$_language_id]) ? $product_description[$_language_id]['seo_keyword'] : ''; ?>" placeholder="<?php echo $_language->get('entry_keyword'); ?>" id="input-seo-keyword<?php echo $_language_id; ?>" class="form-control" />
          <span class="input-group-addon btn btn-primary" data-toggle="tooltip" title="Generate value" onClick="seoPackageGen('seo_keyword', '<?php echo $_language_id; ?>', '')"><i class="fa fa-bolt"></i></span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-seo-h1<?php echo $_language_id; ?>">SEO H1</label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" name="product_description[<?php echo $_language_id; ?>][seo_h1]" value="<?php echo isset($product_description[$_language_id]) ? $product_description[$_language_id]['seo_h1'] : ''; ?>" placeholder="SEO H1" id="input-seo-h1<?php echo $_language_id; ?>" class="form-control" />
          <span class="input-group-addon btn btn-primary" data-toggle="tooltip" title="Generate value" onClick="seoPackageGen('seo_h1', '<?php echo $_language_id; ?>', '')"><i class="fa fa-bolt"></i></span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-seo-h2<?php echo $_language_id; ?>">SEO H2</label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" name="product_description[<?php echo $_language_id; ?>][seo_h2]" value="<?php echo isset($product_description[$_language_id]) ? $product_description[$_language_id]['seo_h2'] : ''; ?>" placeholder="SEO H2" id="input-seo-h2<?php echo $_language_id; ?>" class="form-control" />
          <span class="input-group-addon btn btn-primary" data-toggle="tooltip" title="Generate value" onClick="seoPackageGen('seo_h2', '<?php echo $_language_id; ?>', '')"><i class="fa fa-bolt"></i></span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-seo-h3<?php echo $_language_id; ?>">SEO H3</label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" name="product_description[<?php echo $_language_id; ?>][seo_h3]" value="<?php echo isset($product_description[$_language_id]) ? $product_description[$_language_id]['seo_h3'] : ''; ?>" placeholder="SEO H3" id="input-seo-h3<?php echo $_language_id; ?>" class="form-control" />
          <span class="input-group-addon btn btn-primary" data-toggle="tooltip" title="Generate value" onClick="seoPackageGen('seo_h3', '<?php echo $_language_id; ?>', '')"><i class="fa fa-bolt"></i></span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-image-alt<?php echo $_language_id; ?>">Image alt</label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" name="product_description[<?php echo $_language_id; ?>][image_alt]" value="<?php echo isset($product_description[$_language_id]) ? $product_description[$_language_id]['image_alt'] : ''; ?>" placeholder="Image alt" id="input-image-alt<?php echo $_language_id; ?>" class="form-control" />
          <span class="input-group-addon btn btn-primary" data-toggle="tooltip" title="Generate value" onClick="seoPackageGen('image_alt', '<?php echo $_language_id; ?>', '')"><i class="fa fa-bolt"></i></span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-image-title<?php echo $_language_id; ?>">Image title</label>
      <div class="col-sm-10">
        <div class="input-group">
          <input type="text" name="product_description[<?php echo $_language_id; ?>][image_title]" value="<?php echo isset($product_description[$_language_id]) ? $product_description[$_language_id]['image_title'] : ''; ?>" placeholder="Image title" id="input-image-title<?php echo $_language_id; ?>" class="form-control" />
          <span class="input-group-addon btn btn-primary" data-toggle="tooltip" title="Generate value" onClick="seoPackageGen('image_title', '<?php echo $_language_id; ?>', '')"><i class="fa fa-bolt"></i></span>
        </div>
      </div>
    <script  type="text/javascript"><!--
      $('.btnSeoGen').hover( function(){
        $(this).addClass('btn-primary');
      }, function(){
        $(this).removeClass('btn-primary');
      });
      
      function seoPackageGen(field, lang, store) {
        $.ajax({
          url: 'index.php?route=module/complete_seo/get_value&type=product&id=<?php echo isset($_GET['product_id']) ? $_GET['product_id'] : 0; ?>&field='+field+'&store='+store+'&lang='+lang+'&<?php echo $token_type; ?>=<?php echo $token; ?>',
          method: 'POST',
          data: $('form#form-product').serialize(),
          dataType: 'json',
          success: function(values) {
            jQuery.each( values, function( i, val ) {
              if (field == 'description') {
                if (typeof $('#input-description'+lang).summernote('code') === 'string') {
                $('[name="'+i+'"]').summernote('code', val);
              } else {
                  $('[name="'+i+'"]').code(val);
                }
              } else {
                $('[name="'+i+'"]').val(val);
              }
              $('[name="'+i+'"]').css('transition', '');
              $('[name="'+i+'"]').css('background-color', '#FCFFC6');
              setTimeout(function(){
                $('[name="'+i+'"]').css('transition', 'all 0.5s ease');
                $('[name="'+i+'"]').css('background-color', '');
              }, 10);
            });
          }
        });
      }
    --></script>
    </div>
  <?php } ?>
<?php } ?>