<?php echo $header; ?> 

                <link type="text/css" rel="stylesheet" href="catalog/view/css/account.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/ele-style.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/dashboard.css" />
			
<div class="row"><?php echo $column_left; ?>
<?php
if ($column_left and $column_right) {
    $class="col-lg-8 col-md-6 col-sm-4 col-xs-12";
} elseif ($column_left or $column_right) {
     $class="col-lg-10 col-md-9 col-sm-8 col-xs-12";
} else {
     $class="col-xs-12";
}
?>

        
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/bootstrap.min.css">
		
      
<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
		  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
      <div class="register_content row-fluid">
        <div class="span9">
        <h1><?php echo $heading_title; ?></h1>
        <span class="highlight"><p><?php echo $text_account_already; ?></p></span>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            
            <div class="content ig_register">
                <table class="form" id="desktop">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
                        <td><input type="text" class="form-control" class="form-control" name="firstname" value="<?php echo $firstname; ?>" />
                            <?php if ($error_firstname) { ?>
                                <span class="error"><?php echo $error_firstname; ?></span>
                            <?php } ?></td>
                    
                        <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
                        <td><input type="text" class="form-control" name="lastname" value="<?php echo $lastname; ?>" />
                            <?php if ($error_lastname) { ?>
                                <span class="error"><?php echo $error_lastname; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_email; ?></td>
                        <td><input type="text" class="form-control" name="email" value="<?php echo $email; ?>" />
                            <?php if ($error_email) { ?>
                                <span class="error"><?php echo $error_email; ?></span>
                            <?php } ?></td>
                   
                        <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
                        <td><input type="text" class="form-control" name="telephone" value="<?php echo $telephone; ?>" />
                            <?php if ($error_telephone) { ?>
                                <span class="error"><?php echo $error_telephone; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr style="display:none !important">
                        <td><?php echo $entry_fax; ?></td>
                        <td><input type="text" class="form-control" name="fax" value="<?php echo $fax; ?>" /></td>
                    </tr>
                    <tr>
                        <td ><?php echo $entry_company; ?></td>
                        <td><input type="text" class="form-control" value="<?php echo $company; ?>" /></td>
                    </tr>
                    <tr class="bussiness_company" style="display: table-row;">
                        <td class="compnay-whole">Customer Group</td>
                        <td>    
                         <span style="float:left; margin-right:26px;"> 
                          
                           <input type="radio" class="text-message" data-text="" name="customer_group_id" value="1" id="customer_group_id1">
                            <label for="customer_group_id1">Personal Use</label>
                            </span>
                                 <span style="float:left;margin-right:10px;">       
                            <input type="radio" class="text-message" data-text="" name="customer_group_id" value="2" id="customer_group_id2" checked="checked">
                                    <label for="customer_group_id2">Wholesale</label>
                            </span>  
                                    <div id="radioMessage" style="float:left;width:129%;clear:both;">
                              </div>
                        </td>
                    
                        
                    </tr> 
                </table>

                
            </div>
            <span class="highlight"><?php echo $text_your_address; ?></span>
            <div class="content">
                <table class="form">   
                    <tr class="hidden">
                        <td>Company ID</td>
                        <td><input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
                           
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
                        <td><input type="text" class="form-control" name="address_1" value="<?php echo $address_1; ?>" />
                            <?php if ($error_address_1) { ?>
                                <span class="error"><?php echo $error_address_1; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_address_2; ?></td>
                        <td><input type="text" class="form-control" name="address_2" value="<?php echo $address_2; ?>" /></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_city; ?></td>
                        <td><input type="text" class="form-control" name="city" value="<?php echo $city; ?>" />
                            <?php if ($error_city) { ?>
                                <span class="error"><?php echo $error_city; ?></span>
                            <?php } ?></td>
                    </tr>
                  
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_country; ?></td>
                        <td><select name="country_id" class="form-control">
                                <option value=""><?php echo $text_select; ?></option>
                                <?php foreach ($countries as $country) { ?>
                                    <?php if ($country['country_id'] == $country_id) { ?>
                                        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <?php if ($error_country) { ?>
                                <span class="error"><?php echo $error_country; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
                        <td><select name="zone_id" class="form-control">
                            </select>
                            <?php if ($error_zone) { ?>
                                <span class="error"><?php echo $error_zone; ?></span>
                            <?php } ?></td>
                    </tr>
                      <tr>
                        <td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
                        <td><input type="text" class="form-control" name="postcode" value="<?php echo $postcode; ?>" />
                            <?php if ($error_postcode) { ?>
                                <span class="error"><?php echo $error_postcode; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                     <tr>
                        <td><span id="resale_number"></span> <?php echo $entry_resale_number; ?></td>
                        <td><input type="text" class="form-control" name="resale_number" value="<?php echo $resale_number; ?>" /></td>
                    </tr>
                </table> 
                
            </div>
            <span class="highlight"><?php echo $text_your_password; ?></span>
            <div class="content">
                <table class="form" id="desktop">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_password; ?></td>
                        <td><input type="password"  class="form-control" name="password" value="<?php echo $password; ?>" />
                            <?php if ($error_password) { ?>
                                <span class="error"><?php echo $error_password; ?></span>
                            <?php } ?></td>
                    
                        <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
                        <td><input type="password"  class="form-control" name="confirm" value="<?php echo $confirm; ?>" />
                            <?php if ($error_confirm) { ?>
                                <span class="error"><?php echo $error_confirm; ?></span>
                            <?php } ?></td>
                    </tr>
                </table>
                
            </div>
            <div>
            <h4><?php echo $text_comments; ?></h4>
            <span>Include anything you'd like us to know when are filling your orders or preparing them for shipment. These notes will be saved and our staff will see them on all your future orders. You can make changes later if you can't think of anything right now.</span>
            <p>
            	<textarea name="comment" rows="8" style="width:450px;" class="form-control"><?php echo $comment; ?></textarea>
            </p>
            </div>
            <div class="right">
                <input type="checkbox" name="agree" value="1" checked="checked" />
                <span style="font-size:14px;">I wish to receive emails with specials and promotions from Gempacked.</span>
            </div>
            
            <?php echo $captcha; ?>
            
            <?php if ($text_agree) { ?>
               
                <div class="right">
                    <?php if ($agree) { ?>
                        <input type="checkbox" name="agree" value="1" checked="checked" />
                    <?php } else { ?>
                        <input type="checkbox" name="agree" value="1" checked="checked" />
                    <?php } ?>
                    <span><?php echo $text_agree; ?></span>
                    <input type="submit" value="<?php //echo $button_continue; ?>" class="button-register" />
                </div>
             
            <?php } else { ?>
                <div class="buttons col-sm-6" style="padding-left:0; margin-top:10px;"> 
                    <div class="right">
                        <span class="float:left"> <input type="submit" value="Create Your Account" class="btn btn-info btn-block" /></span>
                    </div>
                </div>
            <?php } ?>
        </form>
    </div>
        <div class="span3">
            <?php echo $column_right; ?>
        </div>
    </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
<script type="text/javascript"><!--
// Sort the custom fields(
        $(document).ready(function(){
            $("#customer_group_id2").click();
            $("#customer_group_id2").prop('checked',true);
            $("#customer_group_id1").prop('checked',false);
        });
$('#account .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
		$('#account .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#account .form-group').length) {
		$('#account .form-group:first').before(this);
	}
});

$('#address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address .form-group').length) {
		$('#address .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#address .form-group').length) {
		$('#address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#address .form-group').length) {
		$('#address .form-group:first').before(this);
	}
});

  $('input[name=\'customer_group_id\']:checked').live('change', function() {
        var customer_group = [];
    
<?php foreach ($customer_groups as $customer_group) { ?>
            customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
<?php } ?>  

        if (customer_group[this.value]) {
            if (customer_group[this.value]['company_id_display'] == '1') {
                $('#company-id-display').show();
            } else {
                $('#company-id-display').hide();
            }
        
            if (customer_group[this.value]['company_id_required'] == '1') {
                $('#company-id-required').show();
            } else {
                $('#company-id-required').hide();
            }
        
        }
    });

    $('input[name=\'customer_group_id\']:checked').trigger('change');

$('input[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/customfield&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('#custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('#custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}


		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('input[name=\'customer_group_id\']:checked').trigger('change');
//--></script>
<script type="text/javascript"><!--
$('button[id^=\'button-custom-field\']').on('click', function() {
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
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>
<?php echo $footer; ?>
