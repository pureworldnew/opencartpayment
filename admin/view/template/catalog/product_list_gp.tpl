<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  
 <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            	<a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>  
                <button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger" onclick="confirm('Are you sure?') ? $('#form').submit() : false;"><i class="fa fa-trash-o"></i></button>
               
                
            	
            </div>
                <h1><?php echo $heading_title; ?></h1>
     
        </div>
        	<?php if (isset($error_warning)) { ?>
          		<div class="warning"><?php echo $error_warning; ?></div>
          	<?php } ?>
          	<?php if (!empty($success)) { ?>
          		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
         	<?php } ?>
      </div>
    
    <div class="container-fluid">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="center" width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $column_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
                <td class="left"><?php echo 'SKU' ?></td>
                 <td class="left"><?php echo 'Group Indicator' ?></td>
                  <td class="left"><?php echo 'Group Indicator ID' ?></td>
              <td class="left" colspan="2"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <!--<td class="left"><?php if ($sort == 'pgt.pg_type') { ?>
                <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_type; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_type; ?>"><?php echo $column_product_type; ?></a>
                <?php } ?></td>              
              <td class="left"><?php echo $column_product_total_grouped; ?></td>-->
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
          <tr class="filter">
              <td></td>
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
              <td align="left"><input type="text" name="filter_indicator" value="<?php echo $filter_indicator; ?>" size="8"/></td>
              <td align="right"><input type="text" name="filter_indicator_id" value="<?php echo $filter_indicator_id; ?>" size="8"/></td>
              <td></td>
              <td></td>
              <td><select name="filter_status">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td align="right"><a onclick="filter();" class="btn btn-primary"><?php echo 'Filter'; ?></a></td>
            </tr>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['id']; ?>" />
                <?php } ?></td>
              
              <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
              <td class="left"><?php echo $product['name']; ?></td>
              <td class="left"><?php echo $product['sku']; ?></td>
              <td class="left"><?php echo $product['groupindicator']; ?></td>
              <td class="left"><?php echo $product['groupindicator_id']; ?></td>
              <?php if ($product['pg_type'] == 'configurable') { ?>
                <?php if ((float)$product['price']) { ?>
                <td class="right" style="border-right:none;"><?php echo $text_price_fixed; ?></td>
                <td class="left"><?php echo $product['price']; ?></td>
                <?php } elseif ((float)$product['price_to']) { ?>         
                <td class="right" style="border-right:none;"><?php echo $text_price_from . '<br />' . $text_price_to; ?></td>
                <td class="left"><?php echo $product['price_from'] . '<br />' . $product['price_to']; ?></td>
                <?php } else { ?>         
                <td class="right" style="border-right:none;"><?php echo $text_price_from; ?></td>
                <td class="left"><?php echo $product['price_from']; ?></td>
                <?php } ?>
              <?php } else { ?>
              <td class="right" style="border-right:none;"><?php echo $text_price_start; ?></td>
              <td class="left"><?php if ($product['special']) { ?>
                <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                <span style="color: #b00;"><?php echo $product['special']; ?></span>
                <?php } else { ?>
                <?php echo $product['price']; ?>
                <?php } ?></td>
              <?php } ?>
              <td class="left"><?php echo $product['status']; ?></td>
              <!--<td class="left"><?php echo ucwords($product['pg_type'] . ' ' . $product['pg_layout']); ?></td>
              <td class="left"><?php echo $product['total_grouped']; ?></td>-->
              <td class="text-right"><?php foreach ($product['action'] as $action) { ?>
                <a href="<?php echo $action['href']; ?>" data-toggle="tooltip" title="Edit Product" class="btn btn-primary"><i class="fa fa-pencil"></i></a> 
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
      <p style="text-align:right;"><a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=fabiom7" target="_blank"><?php echo $modid; ?></a><br /><a href="http://www.fabiom7.com" target="_blank">powered by fabiom7</a> - fabiome77@hotmail.it</p>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('head').append('<style type="text/css">span.button{margin-left:5px;-webkit-border-radius:10px 10px 10px 10px;-moz-border-radius:10px 10px 10px 10px;-khtml-border-radius:10px 10px 10px 10px;border-radius:10px 10px 10px 10px;padding:5px 15px 5px 15px;}span.button,span.button select,span.button select option{background:#003A88;color:#fff;}span.button select{border:0;outline:0;}a.button-invert{margin-left:5px;text-decoration:none;background:#fff;color:#003A88;display:inline-block;padding:5px 15px 5px 15px;-webkit-border-radius:10px 10px 10px 10px;-moz-border-radius:10px 10px 10px 10px;-khtml-border-radius:10px 10px 10px 10px;border-radius:10px 10px 10px 10px;}a.button-invert:hover{background:#003A88;color:#fff;}</style>');
//--></script>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/product_list_gp&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_model = $('input[name=\'filter_model\']').val();
	var filter_indicator_id = $('input[name=\'filter_indicator_id\']').val();
	var filter_indicator = $('input[name=\'filter_indicator\']').val();
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	
	if (filter_indicator) {
		url += '&filter_indicator=' + encodeURIComponent(filter_indicator);
	}
	
	if (filter_indicator_id) {
		url += '&filter_indicator_id=' + encodeURIComponent(filter_indicator_id);
	}
	
	location = url;
}
//--></script> 
<?php echo $footer; ?>