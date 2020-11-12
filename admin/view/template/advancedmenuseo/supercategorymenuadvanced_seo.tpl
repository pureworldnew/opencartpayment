<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_exit; ?>" class="btn btn-danger"><i class="fa fa-reply"></i> <?php echo $button_exit; ?></a> </div>
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
  <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $error_warning; ?>
    
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
    </div>
    <div class="panel-body">
      <ul class="nav nav-tabs" id="tabs_editor">
        <li class="active"><a data-toggle="tab" editor-type="categories" href="#tab-seo-categories"><i class="fa fa-cube"></i>&nbsp;<?php echo $tab_categories ; ?></a></li>
        <li><a data-toggle="tab" editor-type="manufacturer" href="#tab-seo-manufacturer"><i class="fa fa-book"></i>&nbsp;<?php echo $tab_manufacturer ; ?></a></li>
        <li><a data-toggle="tab" editor-type="attributes" href="#tab-seo-attributes"><i class="fa fa-navicon"></i>&nbsp;<?php echo $tab_attributes ; ?></a></li>
        <li><a data-toggle="tab" editor-type="options" href="#tab-seo-options"><i class="fa fa-thumb-tack"></i>&nbsp;<?php echo $tab_options ; ?></a></li>
        <li><a data-toggle="tab" editor-type="product_infos" href="#tab-seo-product_infos"><i class="fa fa-info"></i>&nbsp;<?php echo $tab_product_infos ; ?></a></li>
        <li><a data-toggle="tab" editor-type="review" href="#tab-seo-review"><i class="fa fa-pencil-square-o"></i>&nbsp;<?php echo $tab_review ; ?> & <?php echo $tab_stocks ; ?></a></li>
        <li><a data-toggle="tab" href="#tab-seo-register" id="licensing" ><i class="fa fa-keyboard-o"></i>&nbsp;<?php echo $tab_register; ?></a></li>
        <li><a data-toggle="tab" href="#tab-seo-contact" id="contact" ><i class="fa fa-external-link"></i>&nbsp;<?php echo $tab_contact; ?></a></li>
      </ul>
      <div class="tab-content">
        <div id="tab-seo-options" class="tab-pane">
          <div  id="options_tbl">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_name; ?></td>
                    <td class="text-left"><?php echo $path_name; ?></td>
                    <td class="text-right"><?php echo $seo_word; ?></td>
                    <td class="text-left"><?php echo $suggest; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($options) { ?>
                  <?php foreach ($options as $option) { ?>
                  <tr>
                    <td class="text-left"><?php echo $option['name']; ?></td>
                    <td class="text-left"><?php echo $option['path']; ?></td>
                    <td class="text-right"><input class="form-control" type="text" class="seo_word" onblur="updateSeoWord2('opt_select_<?php echo $option['option_id']; ?>','<?php echo $option['path']; ?>','<?php echo $option['option_id']; ?>','o');" id="seo_word_o<?php echo $option['option_id']; ?>" name="seo_word_o<?php echo $option['option_id']; ?>" data-path="<?php echo $option['path']; ?>" value="<?php echo $option['seo_word']; ?>" ></td>
                    <td class="text-left"><?php if ($option['suggest']) { ?>
                      <select  class="form-control" id="opt_select_<?php echo $option['option_id']; ?>" onchange="$('#seo_word_o<?php echo $option['option_id']; ?>').attr('value', $( '#opt_select_<?php echo $option['option_id']; ?>' ).val());updateSeoWord('opt_select_<?php echo $option['option_id']; ?>','<?php echo $option['path']; ?>','<?php echo $option['option_id']; ?>','o'); " >
                        <option value="">Select Suggestion</option>
                        <?php foreach ($option['suggest'] as $value) {?>
                        <option value="<?php  echo $value; ?>">
                        <?php  echo $value; ?>
                        </option>
                        <?php } ?>
                      </select>
                      <?php } ?></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="row option">
              <div class="col-sm-6 text-left"><?php echo $pagination_opt; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results_opt; ?></div>
            </div>
            <script type="text/javascript">
		  $("div.option ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				 $.ajax({
			       success:showResponseoptionpagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		  function showResponseoptionpagination(responseText, statusText, xhr)  { $('#options_tbl').fadeOut('slow', function(){$("#options_tbl").html(''); $("#options_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	 </script> 
          </div>
        </div>
        <div id="tab-seo-attributes" class="tab-pane">
          <div  id="attributes_tbl">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_name; ?></td>
                    <td class="text-left"><?php echo $path_name; ?></td>
                    <td class="text-right"><?php echo $seo_word; ?></td>
                    <td class="text-left"><?php echo $suggest; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($attributes) { ?>
                  <?php foreach ($attributes as $attribute) { ?>
                  <tr>
                    <td class="text-left"><?php echo $attribute['name']; ?></td>
                    <td class="text-left"><?php echo $attribute['path']; ?></td>
                    <td class="text-right"><input type="text" class="form-control" class="seo_word" onblur="updateSeoWord2('att_select_<?php echo $attribute['attribute_id']; ?>','<?php echo $attribute['path']; ?>','<?php echo $attribute['attribute_id']; ?>','a');" id="seo_word_a<?php echo $attribute['attribute_id']; ?>" name="seo_word_a<?php echo $attribute['attribute_id']; ?>" data-path="<?php echo $attribute['path']; ?>" value="<?php echo $attribute['seo_word']; ?>" ></td>
                    <td class="text-left"><?php if ($attribute['suggest']) { ?>
                      <select class="form-control" id="att_select_<?php echo $attribute['attribute_id']; ?>" onchange="$('#seo_word_a<?php echo $attribute['attribute_id']; ?>').attr('value', $( '#att_select_<?php echo $attribute['attribute_id']; ?>' ).val());updateSeoWord('att_select_<?php echo $attribute['attribute_id']; ?>','<?php echo $attribute['path']; ?>','<?php echo $attribute['attribute_id']; ?>','a'); " >
                        <option value="">Select Suggestion</option>
                        <?php foreach ($attribute['suggest'] as $value) {?>
                        <option value="<?php  echo $value; ?>">
                        <?php  echo $value; ?>
                        </option>
                        <?php } ?>
                      </select>
                      <?php } ?></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="row attribute">
              <div class="col-sm-6 text-left"><?php echo $pagination_att; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results_att; ?></div>
            </div>
            <script type="text/javascript">
		  $("div.attribute ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				 $.ajax({
			       success:showResponseAttributepagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		  function showResponseAttributepagination(responseText, statusText, xhr)  { $('#attributes_tbl').fadeOut('slow', function(){$("#attributes_tbl").html(''); $("#attributes_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	 </script> 
          </div>
        </div>
        <div id="tab-seo-review" class="tab-pane">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-left"><?php echo $path_name; ?></td>
                  <td class="text-right"><?php echo $seo_word; ?></td>
                  <td class="text-left"><?php echo $suggest; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php  if ($stock_reviews) { ?>
                <?php foreach ($stock_reviews as $stock_review) { ?>
                <tr>
                  <td class="text-left"><?php echo $stock_review['name']; ?></td>
                  <td class="text-left"><?php echo $stock_review['path']; ?></td>
                  <td class="text-right"><input class="form-control" type="text" class="seo_word"  onblur="updateSeoWord2('productinfo_select_<?php echo $stock_review['stock_review_id']; ?>','<?php echo $stock_review['path']; ?>','<?php echo $stock_review['stock_review_id']; ?>','p');" id="seo_word_p<?php echo $stock_review['stock_review_id']; ?>" name="seo_word_p<?php echo $stock_review['stock_review_id']; ?>" data-path="<?php echo $stock_review['path']; ?>" value="<?php echo $stock_review['seo_word']; ?>" ></td>
                  <td class="text-left"><?php if ($stock_review['suggest']) { ?>
                    <select class="form-control" id="review_select_<?php echo $stock_review['stock_review_id']; ?>" onchange="$('#seo_word_p<?php echo $stock_review['stock_review_id']; ?>').attr('value', $( '#review_select_<?php echo $stock_review['stock_review_id']; ?>' ).val());updateSeoWord('review_select_<?php echo $stock_review['stock_review_id']; ?>','<?php echo $stock_review['path']; ?>','<?php echo $stock_review['stock_review_id']; ?>','p'); " >
                      <option value="">Select Suggestion</option>
                      <?php foreach ($stock_review['suggest'] as $value) {?>
                      <option value="<?php  echo $value; ?>">
                      <?php  echo $value; ?>
                      </option>
                      <?php } ?>
                    </select>
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div id="tab-seo-categories" class="tab-pane active">
          <div  id="category_tbl">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_name; ?></td>
                    <td class="text-left"><?php echo $path_name; ?></td>
                    <td class="text-right"><?php echo $seo_word; ?></td>
                    <td class="text-left"><?php echo $suggest; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($categories) { ?>
                  <?php foreach ($categories as $category) { ?>
                  <tr>
                    <td class="text-left"><?php echo $category['name']; ?></td>
                    <td class="text-left"><?php echo $category['path']; ?></td>
                    <td class="text-right"><input class="form-control" type="text" class="seo_word" onblur="updateSeoWord2('cat_select_<?php echo $category['category_id']; ?>','<?php echo $category['path']; ?>','<?php echo $category['category_id']; ?>','c');" id="seo_word_c<?php echo $category['category_id']; ?>" name="seo_word_c<?php echo $category['category_id']; ?>" data-path="<?php echo $category['path']; ?>" value="<?php echo $category['seo_word']; ?>" ></td>
                    <td class="text-left"><?php if ($category['suggest']) { ?>
                      <select class="form-control" id="cat_select_<?php echo $category['category_id']; ?>" onchange="$('#seo_word_c<?php echo $category['category_id']; ?>').attr('value', $( '#cat_select_<?php echo $category['category_id']; ?>' ).val());updateSeoWord('cat_select_<?php echo $category['category_id']; ?>','<?php echo $category['path']; ?>','<?php echo $category['category_id']; ?>','c'); " >
                        <option value="">Select Suggestion</option>
                        <?php foreach ($category['suggest'] as $value) {?>
                        <option value="<?php  echo $value; ?>">
                        <?php  echo $value; ?>
                        </option>
                        <?php } ?>
                      </select>
                      <?php } ?></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="row category">
              <div class="col-sm-6 text-left"><?php echo $pagination_cat; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results_cat; ?></div>
            </div>
            <script type="text/javascript">
		  $("div.category ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				 $.ajax({
			       success:showResponseCategoypagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		  function showResponseCategoypagination(responseText, statusText, xhr)  { $('#category_tbl').fadeOut('slow', function(){$("#category_tbl").html(''); $("#category_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	 </script> 
          </div>
        </div>
        <div id="tab-seo-manufacturer" class="tab-pane">
          <div  id="manufacturer_tbl">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_name; ?></td>
                    <td class="text-left"><?php echo $path_name; ?></td>
                    <td class="text-right"><?php echo $seo_word; ?></td>
                    <td class="text-left"><?php echo $suggest; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($manufacturers) { ?>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <tr>
                    <td class="text-left"><?php echo $manufacturer['name']; ?></td>
                    <td class="text-left"><?php echo $manufacturer['path']; ?></td>
                    <td class="text-right"><input class="form-control" type="text" class="seo_word"  onblur="updateSeoWord2('man_select_<?php echo $manufacturer['manufacturer_id']; ?>','<?php echo $manufacturer['path']; ?>','<?php echo $manufacturer['manufacturer_id']; ?>','m');" id="seo_word_m<?php echo $manufacturer['manufacturer_id']; ?>" name="seo_word_m<?php echo $manufacturer['manufacturer_id']; ?>" data-path="<?php echo $manufacturer['path']; ?>" value="<?php echo $manufacturer['seo_word']; ?>" ></td>
                    <td class="text-left"><?php if ($manufacturer['suggest']) { ?>
                      <select class="form-control" id="man_select_<?php echo $manufacturer['manufacturer_id']; ?>" onchange="$('#seo_word_m<?php echo $manufacturer['manufacturer_id']; ?>').attr('value', $( '#man_select_<?php echo $manufacturer['manufacturer_id']; ?>' ).val());updateSeoWord('man_select_<?php echo $manufacturer['manufacturer_id']; ?>','<?php echo $manufacturer['path']; ?>','<?php echo $manufacturer['manufacturer_id']; ?>','m'); " >
                        <option value="">Select Suggestion</option>
                        <?php foreach ($manufacturer['suggest'] as $value) {?>
                        <option value="<?php  echo $value; ?>">
                        <?php  echo $value; ?>
                        </option>
                        <?php } ?>
                      </select>
                      <?php } ?></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="row manufacturer">
              <div class="col-sm-6 text-left"><?php echo $pagination_man; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results_man; ?></div>
            </div>
            <script type="text/javascript">
		  $("div.manufacturer ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				 $.ajax({
			       success:showResponsemanufacturerpagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		  function showResponsemanufacturerpagination(responseText, statusText, xhr)  { $('#manufacturer_tbl').fadeOut('slow', function(){$("#manufacturer_tbl").html(''); $("#manufacturer_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	      </script> 
          </div>
        </div>
        <div id="tab-seo-product_infos" class="tab-pane">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-left"><?php echo $path_name; ?></td>
                  <td class="text-right"><?php echo $seo_word; ?></td>
                  <td class="text-left"><?php echo $suggest; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php  if ($product_infos) { ?>
                <?php foreach ($product_infos as $product_info) { ?>
                <tr>
                  <td class="text-left"><?php echo $product_info['name']; ?></td>
                  <td class="text-left"><?php echo $product_info['path']; ?></td>
                  <td class="text-right"><input class="form-control" type="text" class="seo_word"  onblur="updateSeoWord2('productinfo_select_<?php echo $product_info['productinfo_id']; ?>','<?php echo $product_info['path']; ?>','<?php echo $product_info['productinfo_id']; ?>','p');" id="seo_word_p<?php echo $product_info['productinfo_id']; ?>" name="seo_word_p<?php echo $product_info['productinfo_id']; ?>" data-path="<?php echo $product_info['path']; ?>" value="<?php echo $product_info['seo_word']; ?>" ></td>
                  <td class="text-left"><?php if ($product_info['suggest']) { ?>
                    <select class="form-control" id="product_info_select_<?php echo $product_info['productinfo_id']; ?>" onchange="$('#seo_word_p<?php echo $product_info['productinfo_id']; ?>').attr('value', $( '#product_info_select_<?php echo $product_info['productinfo_id']; ?>' ).val());updateSeoWord('product_info_select_<?php echo $product_info['productinfo_id']; ?>','<?php echo $product_info['path']; ?>','<?php echo $product_info['productinfo_id']; ?>','p'); " >
                      <option value="">Select Suggestion</option>
                      <?php foreach ($product_info['suggest'] as $value) {?>
                      <option value="<?php  echo $value; ?>">
                      <?php  echo $value; ?>
                      </option>
                      <?php } ?>
                    </select>
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div id="tab-seo-register" class="tab-pane">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-register" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-enable"><?php echo $register_status; ?></span></label>
              <div class="col-sm-10">
                <input type="hidden" name="supercategorymenuadvanced_code" value="<?php echo $settings_code; ?>" />
                <?php echo $supercategorymenuadvanced_accountDetails; ?>
                <div id="addcode"></div>
              </div>
            </div>
          </form>
        </div>
        <script type="text/javascript">        
        	<?php if (!$rg){ ?>
			$('#licensing').css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': 'red'});
           	<?php }else{ ?>
			//$('#licensing').prepend('<i class="fa fa-check"></i> '); 
			$('#licensing').css({'color': 'green'});
			<?php } ?>
  			</script>
        <div id="tab-seo-contact" class="tab-pane">
          <table width="100%" border="0" cellpadding="2">
            <tr>
              <td width="4%" valign="top"></td>
              <td width="96%" rowspan="2"><table width="100%" border="0" cellpadding="2">
                  <tr>
                    <td rowspan="4"><span style="font-size:150pt;"><i class="fa fa-connectdevelop fa-6x"></i></span></td>
                    <td>Contact: (Only registered users will be supported)</td>
                    <td><a href="http://support.ocmodules.com" target="_blank">support.ocmodules.com</a></td>
                  </tr>
                  <tr>
                    <td>Current Version:</td>
                    <td><?php echo $current_version; ?>
                      <div id="newversion"></div></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><div id="what_is_new"></div></td>
                  </tr>
                  <tr>
                    <td height="40">OpenCart Url</td>
                    <td><a href="http://www.opencart.com/index.php?route=extension/extension/info&amp;extension_id=<?php echo $version['extension_opencart_url']; ?>" target="_blank"> http://www.opencart.com/index.php?route=extension/extension/info&amp;extension_id=<?php echo $version['extension_opencart_url']; ?></a></td>
                  </tr>
                </table>
                <br />
                <br />
                <?php if (isset($version['modules'])){ ?>
                <strong>Other modules:</strong><br />
                <table  border="0"  cellpadding="2">
                  <?php foreach ($version['modules'] as $modules) { ?>
                  <tr>
                    <td  height="66"><strong><br />
                      <?php echo $modules['name']; ?> - v<?php echo $modules['version']; ?></strong><br />
                      <?php echo str_replace("@@@","<br>",$modules['resume']); ?><br />
                      OC: <a href="http://www.opencart.com/index.php?route=extension/extension/info&amp;extension_id=<?php echo $modules['extension_opencart_url']; ?>" target="_blank">http://www.opencart.com/index.php?route=extension/extension/info&extension_id=<?php echo $modules['extension_opencart_url']; ?> </a>
                      <?php if ($modules['video']) { ?>
                      Video: <a href="<?php echo $modules['video']; ?>" target="_blank"><br />
                      <?php } ?></td>
                  </tr>
                  <?php } ?>
                </table>
                <?php } ?></td>
              <?php  if ($version){ 
			 if ($version['current_version']!=$current_version){ ?>
			 <script type="text/javascript">
               $('#contact').css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': 'red','text-decoration': 'blink'});
               $('#newversion').append ('<span style=\"color:red\"><strong>New version for this extension available <?php echo $version["current_version"]; ?></strong></span>');
               $('#what_is_new').append("<?php echo html_entity_decode(str_replace("@@@","<br>",$version['whats_new']), ENT_QUOTES, 'UTF-8'); ?>");
              </script>
              <?php } else{ ?>
              <script type="text/javascript">
            
			$('#contact').css({'color': 'green'});
			$('#newversion').append ('<span style=\"color:green\"><strong>Correct, you have the last version.</strong></span>');
	        </script>
              <?php  }} ?>
            </tr>
            <tr>
              <td valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td width="4%" valign="top">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
		function updateSeoWord(selector,path,id,str){
			var seo_word= $('#'+selector).val();
			var str = str;
			var path  = path;
		    var id  = id;
		        $.ajax({
					url: 'index.php?route=advancedmenuseo/supercategorymenuadvancedseo/save&token=<?php echo $token; ?>&seo_word=' +  encodeURIComponent(seo_word) + '&path=' +  encodeURIComponent(path),
					dataType: 'json',
					beforeSend: function() {
					},  
					complete: function() {
					},              
					success: function(json) {
						$('.alert, .text-danger').remove();
						$('input').removeClass('has-error');
						 if (json['error']) {
						    $('#seo_word_'+str+id).after('<div class="text-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					   		$('#seo_word_'+str+id).addClass('has-error');	
					     }else if (json['success']){
							$('#seo_word_'+str+id).after('<div class="text-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '</div>');
					   	 }
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				}); 
				}
		function updateSeoWord2(selector,path,id,str){
			var seo_word= $('#seo_word_'+id).val();
			var str = str;
			var path  = path;
		    var id  = id;
		        $.ajax({
					url: 'index.php?route=advancedmenuseo/supercategorymenuadvancedseo/save&token=<?php echo $token; ?>&seo_word=' +  encodeURIComponent(seo_word) + '&path=' +  encodeURIComponent(path),
					dataType: 'json',
					beforeSend: function() {
					},  
					complete: function() {
					},              
					success: function(json) {
						$('.alert, .text-danger').remove();
						$('input').removeClass('has-error');
						 if (json['error']) {
						    $('#seo_word_'+str+id).after('<div class="text-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					   		$('#seo_word_'+str+id).addClass('has-error');	
					     }else if (json['success']){
							$('#seo_word_'+str+id).after('<div class="text-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '</div>');
					   	 }
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				}); 
				}
		$("a.register").click(function() {
		html='<br>';
		html='Please enter register code:<br>';
		html+=' <input class="form-control col-sm-3" type="input" name="supercategorymenuadvanced_code" value="" />';
		html+='<br><button type="submit" form="form-register" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo $button_save; ?></button>';
		$('div#addcode').html(html);
	});
	 $('a.register').fancybox({'type':'iframe','transitionIn':'elastic','transitionOut':'elastic','speedIn':600,'speedOut':200,'scrolling':'no','showCloseButton':true,'overlayShow':false,'autoDimensions':false,'width': 600,'height': 300,});		
	</script> 
<?php echo $footer; ?>