<?php if ($options) { ?>
<input type="hidden" id="base_price_input" value="<?php echo isset($base_price) ? $base_price : '0' ?>">
<input id="plural_unit" type="hidden" value ="<?php echo $unit_plural; ?>">
<input type="hidden" class="unit_conversion_values" name ="unit_conversion_values" value="">
<input type="hidden" id="default_conversion_value_name" name ="default_conversion_value_name" value="">
<div id="option-data" style="display:none;">
<h3><?php echo $text_option; ?></h3>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'select') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
  <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
    <option value=""><?php echo $text_select; ?></option>
    <?php foreach ($option['product_option_value'] as $option_value) { ?>
    <option value="<?php echo $option_value['product_option_value_id']; ?>" <?php if(in_array($option_value['product_option_value_id'], $group_product_options)) { echo 'selected'; } ?>><?php echo $option_value['name']; ?>
    <?php if ($option_value['price']) { ?>
    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
    <?php } ?>
    </option>
    <?php } ?>
  </select>
</div>
<?php } ?>
<?php if ($option['type'] == 'radio') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label"><?php echo $option['name']; ?></label>
  <div id="input-option<?php echo $option['product_option_id']; ?>">
    <?php foreach ($option['product_option_value'] as $option_value) { ?>
    <div class="radio">
      <label>
        <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
        <?php if ($option_value['image']) { ?>
        <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
        <?php } ?>
        <?php echo $option_value['name']; ?>
        <?php if ($option_value['price']) { ?>
        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
        <?php } ?>
      </label>
    </div>
    <?php } ?>
  </div>
</div>
<?php } ?>
<?php if ($option['type'] == 'checkbox') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label"><?php echo $option['name']; ?></label>
  <div id="input-option<?php echo $option['product_option_id']; ?>">
    <?php foreach ($option['product_option_value'] as $option_value) { ?>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
        <?php if ($option_value['image']) { ?>
        <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
        <?php } ?>
        <?php echo $option_value['name']; ?>
        <?php if ($option_value['price']) { ?>
        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
        <?php } ?>
      </label>
    </div>
    <?php } ?>
  </div>
</div>
<?php } ?>
<?php if ($option['type'] == 'text') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
</div>
<?php } ?>
<?php if ($option['type'] == 'textarea') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
  <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
</div>
<?php } ?>
<?php if ($option['type'] == 'file') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label"><?php echo $option['name']; ?></label>
  <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
  <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
</div>
<?php } ?>
<?php if ($option['type'] == 'date') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
  <div class="input-group date">
    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
    <span class="input-group-btn">
    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
    </span></div>
</div>
<?php } ?>
<?php if ($option['type'] == 'datetime') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
  <div class="input-group datetime">
    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
    <span class="input-group-btn">
    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
    </span></div>
</div>
<?php } ?>
<?php if ($option['type'] == 'time') { ?>
<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
  <div class="input-group time">
    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
    <span class="input-group-btn">
    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
    </span></div>
</div>
<?php } ?>
<?php } ?>
</div>
<div class="row">
  <div class="col-md-4">
    <img src="<?php echo $thumb; ?>" alt="" class="img-thumbnail img-responsive">
  </div>
  <div class="col-md-8">
    <table class="table">
      <tr>
        <th style="border-top:none;">Price</th>
        <td style="color: #ff4040;border-top:none;font-weight: 600;"><span id="new_price"><?php echo $price; ?></span><span class="unit"> /</span> <span id="quantity_span"> </span>&nbsp;<span id="unit_dis">  <?php echo $unit_singular; ?></span>
        <br>
        <span id="converstion_string_display" style="background-color: rgb(69, 106, 158); font-size:12px; color: rgb(255, 255, 255);"></span>
        </td>
      </tr>
      <?php foreach ($concat_group_product_options as $concat_group_product_option) { ?>
        <tr>
          <th><?php echo $concat_group_product_option['optionname']; ?></th>
          <td><?php echo $concat_group_product_option['optionvalue']; ?></td>
        </tr>
      <?php } ?> 
      <tr>
        <th style="border-top:none;" colspan="2"></th>
      </tr>
      <tr>
        <th style="border-top:none;">Quantity</th>
        <td style="border-top:none;"><input type="text" name="quantity" id="quantity" value="1" class="form-control"></td>
      </tr>
      <?php if( !empty( $unit_datas ) ) { ?>

      <tr>
        <th style="border-top:none;">Unit</th>
        <td style="border-top:none;">
          <div class="ig_MetalType ig_Units units_grouped">
            <select class="get-unit-data form-control" id="get-unit-data">
                <?php foreach ($unit_datas as $unit_data) { ?>
                  <option name="<?php echo $unit_data['name']; ?>" data-value ="<?php echo $unit_data['unit_conversion_product_id']; ?>" value="<?php echo $unit_data['convert_price']; ?>"><?php echo $unit_data['name']; ?></option>
                <?php } ?>
            </select>
          </div>
        </td>
      </tr>

      <?php } ?>
    </table>
  </div>
</div>
<?php } ?>

<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$("#get-unit-data").bind('change', function() {
    GroupProduct.addUnit();
    GroupProduct.updatePrice();
});

$("#quantity").on('change', function() {
  $("#get-unit-data").trigger("change");
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').val(json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$(document).ready(function() {
  var default_conversion_value_name = $("#get-unit-data").find('option:selected').attr('name');
  $('#default_conversion_value_name').val(default_conversion_value_name);
});
//--></script>
