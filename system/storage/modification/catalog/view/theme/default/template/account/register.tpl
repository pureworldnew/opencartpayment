<?php echo $header; ?>

                <link type="text/css" rel="stylesheet" href="catalog/view/css/account.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/ele-style.css" />
				<link type="text/css" rel="stylesheet" href="catalog/view/theme/default/stylesheet/dashboard.css" />
			
<div class="container dashboard">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="register_content row-fluid">
        <div class="col-md-6">
        <h1 type="button" class="btn btn-default btn-block" style="color: #333;background-color: #e6e6e6;border-color: #adadad;text-align:left;cursor:auto;font-weight:bold;font-size:18px;border-radius:0;margin-bottom:20px;">Create Account</h1>
        
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            
            <div class="content ig_register" style="margin-bottom:0 !important">
                <table class="form" style="margin-bottom:0 !important">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
                        <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                            <?php if ($error_firstname) { ?>
                                <span class="error"><?php echo $error_firstname; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
                        <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                            <?php if ($error_lastname) { ?>
                                <span class="error"><?php echo $error_lastname; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_email; ?></td>
                        <td><input type="text" name="email" value="<?php echo $email; ?>" />
                            <?php if ($error_email) { ?>
                                <span class="error"><?php echo $error_email; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
                        <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                            <?php if ($error_telephone) { ?>
                                <span class="error"><?php echo $error_telephone; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_company; ?></td>
                        <td><input type="text" value="<?php echo $company; ?>" /></td>
                    </tr> 
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_password; ?></td>
                        <td><input type="password" name="password" value="<?php echo $password; ?>" />
                            <?php if ($error_password) { ?>
                                <span class="error"><?php echo $error_password; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
                        <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
                            <?php if ($error_confirm) { ?>
                                <span class="error"><?php echo $error_confirm; ?></span>
                            <?php } ?></td>
                    </tr>    
                    <tr class="bussiness_company" style="display: table-row;">
                        <td class="compnay-whole">Customer Group</td>
                        <td>    
                         <span style="float:left; margin-right:26px;"> 
                          
                           <input type="radio" class="text-message" data-text="" name="customer_group_id" value="1" id="customer_group_id1">
                   <label for="customer_group_id1">Default</label>
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
            <div class="right">
                <input type="checkbox" name="agree" value="1" checked="checked" />
                <span style="font-size:14px;"><strong style="position: relative;top: 3px;left: 3px;">Tell me about sales and deals periodically.</strong><br><span style="position: relative;left: 20px;">(We won't spam you, here is our full <a href="/privacy-policy">Privacy Policy</a>.)</span></span> 
            </div>

            <?php if ($text_agree) { ?>
               
                <div class="right">
                    <?php if ($agree) { ?>
                        <input type="checkbox" name="agree" value="1" checked="checked" />
                    <?php } else { ?>
                        <input type="checkbox" name="agree" value="1" checked="checked" />
                    <?php } ?>
                    <span><?php echo $text_agree; ?></span>
                    <input type="submit" class="btn btn-info btn-block" value="Create Your Account" class="button-register" />
                </div>
             
            <?php } else { ?>
                <div class="buttons">
                    <input type="submit" class="btn btn-info btn-block" value="Create Your Account" class="button-register" style="margin-top:15px;" />
                    <h3 type="button" id="account_already" class="btn btn-default btn-block" style="color: #333;background-color: #e6e6e6;border-color: #adadad;text-align:left;cursor:auto;font-weight:bold;font-size:13px;border-radius:0;margin-top:15px;"><?php echo $text_account_already; ?></h3>                        
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
</div>
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
