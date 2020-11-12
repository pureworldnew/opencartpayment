<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-giveaway" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_giveaway; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-giveaway" class="form-horizontal">
          <div class="tab-content">
            <div class="tab-pane active">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
					<div class="col-sm-10">
					<select name="giveaway_status" id="input-status" class="form-control">
						<?php if ($giveaway_status) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
					</div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-minimum-order"><?php echo $entry_minimum; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="giveaway_minimum_order" value="<?php echo $giveaway_minimum_order; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum-order" class="form-control" />
                </div>
              </div>
			  <div class="form-group">
                <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="giveaway_width" value="<?php echo $giveaway_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                </div>
              </div>
			  <div class="form-group">
                <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="giveaway_height" value="<?php echo $giveaway_height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                  <div id="giveaway-product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($giveaway_products as $giveaway_product) { ?>
                    <div id="giveaway-product<?php echo $giveaway_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $giveaway_product['name']; ?>
                      <input type="hidden" name="giveaway_product[]" value="<?php echo $giveaway_product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
    if(request.length > 2)
    {
      $.ajax({
        url: 'index.php?route=catalog/product/autocompletegiveaway&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
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
    }
	},
	'select': function(item) {
		$('input[name=\'product\']').val('');
		
		$('#giveaway-product' + item['value']).remove();
		
		$('#giveaway-product').append('<div id="giveaway-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="giveaway_product[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#giveaway-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

//--></script>
  
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>