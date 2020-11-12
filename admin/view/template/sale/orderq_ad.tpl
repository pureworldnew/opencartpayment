<form method="post" enctype="multipart/form-data" id="form-address" class="form-horizontal">
<input type="hidden" name="address_id" value="<?php echo $address_id; ?>" />
<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
<input type="hidden" name="addresstype" value="" />
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-address-firstname"><?php echo $entry_firstname; ?></label>
<div class="col-sm-10">
  <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-address-firstname" class="form-control" />
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-address-lastname"><?php echo $entry_lastname; ?></label>
<div class="col-sm-10">
  <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-address-lastname" class="form-control" />
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label" for="input-address-company"><?php echo $entry_company; ?></label>
<div class="col-sm-10">
  <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-address-company" class="form-control" />
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-address-1"><?php echo $entry_address_1; ?></label>
<div class="col-sm-10">
  <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label" for="input-address-2"><?php echo $entry_address_2; ?></label>
<div class="col-sm-10">
  <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" class="form-control" />
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-address-city"><?php echo $entry_city; ?></label>
<div class="col-sm-10">
  <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-address-city" class="form-control" />
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-address-postcode"><?php echo $entry_postcode; ?></label>
<div class="col-sm-10">
  <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-address-postcode" class="form-control" />
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-address-country"><?php echo $entry_country; ?></label>
<div class="col-sm-10">
  <select name="country_id" id="input-address-country" onchange="country(this, '', '<?php echo $zone_id; ?>');" class="form-control">
    <option value=""><?php echo $text_select; ?></option>
    <?php foreach ($countries as $country) { ?>
    <?php if ($country['country_id'] == $country_id) { ?>
    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
    <?php } else { ?>
    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
    <?php } ?>
    <?php } ?>
  </select>
</div>
</div>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-address-zone"><?php echo $entry_zone; ?></label>
<div class="col-sm-10">
  <select name="zone_id" id="input-address-zone" class="form-control">
    <option value=""><?php echo $text_select; ?></option>
    <?php foreach ($zones as $zone) { ?>
    <?php if ($zone['zone_id'] == $zone_id) { ?>
    <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
    <?php } else { ?>
    <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
    <?php } ?>
    <?php } ?>
  </select>
</div>
</div>
<?php foreach ($custom_fields as $custom_field) { ?>
  <?php if ($custom_field['location'] == 'address') { ?>
  <?php if ($custom_field['type'] == 'select') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label" for="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
        <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) { ?>
        <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'radio') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>">
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
        <div class="radio">
          <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) { ?>
          <label>
            <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } else { ?>
          <label>
            <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'checkbox') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>">
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
        <div class="checkbox">
          <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $address_custom_field[$custom_field['custom_field_id']])) { ?>
          <label>
            <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } else { ?>
          <label>
            <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'text') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label" for="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'textarea') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label" for="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'file') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <button type="button" id="button-address-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
      <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : ''); ?>" />
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'date') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label" for="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div class="input-address-group date">
        <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
        <span class="input-address-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'time') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label" for="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div class="input-address-group time">
        <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
        <span class="input-address-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'datetime') { ?>
  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
    <label class="col-sm-2 control-label" for="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div class="input-address-group datetime">
        <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-address-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
        <span class="input-address-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
  </div>
  <?php } ?>
  <?php } ?>
  <?php } ?>
</form>