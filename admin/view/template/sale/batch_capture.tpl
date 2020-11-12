<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      	<input type="hidden" value="<?=$logedin_user?>" name="username"/>
        <a class="btn btn-info" href="<?php echo $order_page; ?>">Back to Orders</a>
      </div>
      <h1><?php echo 'Batch Capture - Search Results'; ?></h1>
    </div>
  </div>
  <link type="text/css" href="view/stylesheet/product_labels/stylesheet.css" rel="stylesheet" media="screen" />
  <script type="text/javascript" src="view/javascript/product_labels/pdfobject.js"></script>
  <script type="text/javascript" src="view/javascript/product_labels/product_lebel_edit.js"></script>
  <div class="container-fluid">
  	<?php if(isset($error_upload)){ ?>
      <div style="color: #a94442; background-color: #f2dede; border-color: #ebccd1;padding: 10px;" class=""><?php foreach($error_upload as $error){ echo $error."<br/>";  } ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo 'Orders List'; ?></h3>
      </div>
      <div class="panel-body">
        <form method="post" enctype="multipart/form-data" id="form-batch-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-center" style="width: 7%;">Order ID</td>
                  <td class="text-center">Date</td>
                  <td class="text-center">Status</td>
                  <td class="text-center">Authorization ID/Buyer Name</td>
                  <td class="text-center">Authorization Amount</td>
                  <td class="text-center">Capture Amount</td>
                  <td class="text-center">Action</td>
                </tr>
              </thead>
              <tbody>
                <input type="hidden" name="ppat_env" value="<?php echo $ppat_env; ?>" />
				        <input type="hidden" name="ppat_api_user" value="<?php echo $ppat_api_user; ?>" />
				        <input type="hidden" name="ppat_api_pass" value="<?php echo $ppat_api_pass; ?>" />
				        <input type="hidden" name="ppat_api_sig" value="<?php echo $ppat_api_sig; ?>" />			                
                <?php if ($orders) { ?>
                  <?php foreach ($orders as $key => $order) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                        <?php } ?>
                        <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" />
                        <input type="hidden" name="ppat_order_id[]" value="<?php echo $order['order_id']; ?>" />
                        <input type="hidden" name="ppat_order_status_id[]" value="<?php echo $order['order_status_id']; ?>" />
                      </td>
                      <td class="text-center"><?php echo $order['order_id']; ?></td>
                      <td class="text-center"><?php echo $order['date_added']; ?></td>
                      <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $order['order_status_id']) { ?>
                          <td id="order_status_column_<?php echo $order['order_id']; ?>" class="text-center" style="background: #<?php echo $order['order_status_color']; ?>"><?php echo $order_status['name']; ?></td>
                        <?php } ?>
                      <?php } ?>
                      <td class="text-center"><?php echo $order['authorization_id'] . ' / ' . $order['firstname']. ' ' . $order['lastname'] ; ?></td>
                      <td class="text-center"><?php echo $order['authorization_amount']; ?></td>
                      <td class="text-center"><input type="number" name="ppat_amount_<?php echo $order['order_id']; ?>" class="form-control" value="<?php echo $order['capture_amount']; ?>" /></td>
                      <td class="text-center">
                        <select class="form-control ppat_action_class" style="border-radius: 5px;"  id="ppat_action_<?php echo $order['order_id']; ?>" name="ppat_action_<?php echo $order['order_id']; ?>">
                          <option value="FALSE">---</option>
                          <option value="Complete">Capture &amp; Close</option>
                          <option value="NotComplete">Capture</option>
                          <option value="Void">Void</option>
                          <option value="Full">Full Refund</option>
                          <option value="Partial">Partial Refund</option>
                        </select>
                      </td>
                    </tr>
                    <tr id="pp_resp_<?php echo $order['order_id']; ?>" style="display: none;" class="pp_resp">
                      <td colspan="8">
                        <div class="text">
                          <p style="padding-left: 1%;" id="ppat_response_<?php echo $order['order_id']; ?>"></p>
                          <p style="padding-left: 1%;" id="msgsent_<?php echo $order['order_id']; ?>" style="display:none;"></p>
                          <p style="padding-left: 1%;" id="msgrcvd_<?php echo $order['order_id']; ?>" style="display:none;"></p>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                  <tr><td colspan="8"></td><tr/>
                  <tr>
                    <td class="text-right" colspan="4"><button id="update-statuses-submit" class="btn btn-primary">Update Statuses</button></td>
                    <td class="text-center"><select class="form-control" style="border-radius: 5px;"  id="update-statuses">
                      <option value="FALSE">---</option>
                      <?php foreach ($order_statuses as $order_status) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    </select></td>
                    <td class="text-right"><button id="update-actions-submit" class="btn btn-primary">Update Actions</button></td>
                    <td class="text-center"><select class="form-control" style="border-radius: 5px;"  id="update-actions">
                      <option value="FALSE">---</option>
                      <option value="Complete">Capture &amp; Close</option>
                      <option value="NotComplete">Capture</option>
                      <option value="Void">Void</option>
                      <option value="Full">Full Refund</option>
                      <option value="Partial">Partial Refund</option>
                    </select></td>
                    <td class="text-center"><button id="batch-submit" class="btn btn-primary"><i class="fa fa-dollar"></i> Submit</button></td>
                  </tr>
                <?php } else { ?>
                <tr>
                  <td colspan="8" class="text-center"><?php if($unwanted_order == 1) { echo "<i>Batch Capture only works with Paypal Express Checkout. Kindly select only those orders whose payment method is Paypal Express Checkout.</i>"; } else { echo "No Order Selected!"; } ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left">
            <div id="trans-results" style="border: 1px solid black; background: beige; padding: 5px 20px; width: 50%; display: none;">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
<style type="text/css"> 
  @import "http://fonts.googleapis.com/css?family=Droid+Sans";
  #maindiv{
    width:960px;
    margin:10px auto;
    padding:10px;
    font-family:'Droid Sans',sans-serif
  }
  #formdiv{
    width:500px;
    float:left;
    text-align:center
  }
  .upload{
    background-color:red;
    border:1px solid red;
    color:#fff;
    border-radius:5px;
    padding:10px;
    text-shadow:1px 1px 0 green;
    box-shadow:2px 2px 15px rgba(0,0,0,.75)
  }
  .upload:hover{
    cursor:pointer;
    background:#c20b0b;
    border:1px solid #c20b0b;
    box-shadow:0 0 5px rgba(0,0,0,.75)
  }
  #file{
    color:green;
    padding:5px;
    border:1px dashed #123456;
    background-color:#f9ffe5
  }
  #upload{
    margin-left:45px
  }
  #noerror{
    color:green;
    text-align:left
  }
  #error{
    color:red;
    text-align:left
  }
  #img{
    width:17px;
    border:none;
    height:17px;
    margin-left:-20px;
    margin-bottom:91px
  }
  .abcd{
    text-align:center
  }
  .abcd img{
    height:100px;
    width:100px;
    padding:5px;
    border:1px solid #e8debd
  }
  .btn-grey {
    color: #fff;
    background-color: #808080;
    border-color: #808080;
  }
  .btn-grey:hover {
    color: #fff;
    background-color: #696969;
    border-color: #696969;
  }
</style>
<script type="text/javascript"><!--
  $('button[id^=\'batch-submit\']').on('click', function(e){
    e.preventDefault();
    if(!confirm('Are you sure?')){return false;}
    var selected      = $('input[name^=\'selected\']:checked');
    var ppat_env      = $('input[name=ppat_env]').val();
    var ppat_api_user = $('input[name=ppat_api_user]').val();
    var ppat_api_pass = $('input[name=ppat_api_pass]').val();
    var ppat_api_sig  = $('input[name=ppat_api_sig]').val();
    var validated     = true;
    var data          = [];
    data              = {"ppat_env":ppat_env,"ppat_api_user":ppat_api_user,"ppat_api_pass":ppat_api_pass,"ppat_api_sig":ppat_api_sig};
    if(selected.length == 0){
      alert('Select Any Order To Proceed!');
      return false;
    }
    for(i = 0; i < selected.length; i++){
      var orderId;
      var amount;
      var transaction_action;
      if(orderId = $(selected[i]).parent().parent().find('input[name^=\'ppat_order_id\']').val()){
        amount              = parseFloat($('input[name=ppat_amount_' + orderId + ']').val());
        transaction_action  = $("#ppat_action_" + orderId +" :selected").val();
        if(amount < 0){
          validated = false;
          alert('Enter Valid Amount Against Order ID: '+ orderId);
        }
        if(transaction_action == 'FALSE'){
          validated = false;
          alert('Select A Valid Action Against Order ID: '+ orderId);
        }
        if(validated){}else{break;}
      } 
    }
    if(validated){
      var success_trans   = 0;
      var failed_trans    = 0;
      var total_captured  = 0.00;
      var total_voided    = 0.00;
      var total_refunded  = 0.00;

      for(i = 0; i < selected.length; i++){
        var orderId;
        var orderdata;
        var amount;
        var transaction_action;
        if(orderId = $(selected[i]).parent().parent().find('input[name^=\'ppat_order_id\']').val()){
          amount              = parseFloat($('input[name=ppat_amount_' + orderId + ']').val());
          transaction_action  = $("#ppat_action_" + orderId +" :selected").val();
          orderdata           = {"ppat_amount": amount,"ppat_action":transaction_action,"ppat_order_id": orderId};
          Object.assign(data, orderdata);

          $.ajax({
            url: 'index.php?route=sale/order/ppat_doaction&order_id='+ orderId +'&token=<?php echo $token; ?>',
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function(){
              $('#batch-submit').attr('disabled', 'disabled');
            },
            complete: function(){
              $('#batch-submit').removeAttr('disabled');
            },
            success: function(json){
              if(json['error']){
                failed_trans++;
                var json_error = json['error'];
                json_error = json_error.replace('Failure:', '<strong style="color:red;">Failure:</strong>');
                json_error = json_error.replace('Error:', '<strong style="color:red;">Error:</strong>');
                json_error += ' &nbsp;<button class="btn btn-primary btn-sm" id="paypal_admintool_see_details_'+ json['rcvdorderid'] +'">See Details</button>';
                $('#ppat_response_'+ json['rcvdorderid']).html('<div class="warning">' + json_error + '</div>');
                $('#pp_resp_'+ json['rcvdorderid']).fadeIn('slow');
              }

              if(json['success']){
                success_trans++;
                var json_success = json['success'];
                json_success = '<strong style="color:green;">Success: </strong>' + json_success;
                $('#ppat_response_'+ json['rcvdorderid']).html('<div class="success">' + json_success + '</div>');
                $('#pp_resp_'+ json['rcvdorderid']).fadeIn('slow');
                if($("#ppat_action_" + json['rcvdorderid'] +" :selected").val() === 'Void'){
                  total_voided    = parseFloat(total_voided) + parseFloat($('input[name=ppat_amount_' + json['rcvdorderid'] + ']').val());
                }else if($("#ppat_action_" + json['rcvdorderid'] +" :selected").val() === 'Full' || $("#ppat_action_" + json['rcvdorderid'] +" :selected").val() === 'Partial'){
                  total_refunded  = parseFloat(total_refunded) + parseFloat($('input[name=ppat_amount_' + json['rcvdorderid'] + ']').val());
                }else if($("#ppat_action_" + json['rcvdorderid'] +" :selected").val() === 'Complete' || $("#ppat_action_" + json['rcvdorderid'] +" :selected").val() === 'NotComplete'){
                  total_captured  = parseFloat(total_captured) + parseFloat($('input[name=ppat_amount_' + json['rcvdorderid'] + ']').val());
                }
              }

              if(json['sent']){
                $('#msgsent_'+ json['rcvdorderid']).html(json['sent']);
                $('#msgsent_'+ json['rcvdorderid']).hide();
              }

              if(json['rcvd']){
                $('#msgrcvd_'+ json['rcvdorderid']).html(json['rcvd']);
                $('#msgrcvd_'+ json['rcvdorderid']).hide();
              }

              $('#paypal_admintool_see_details_'+ json['rcvdorderid']).on('click', function(){
                $(this).remove();
                $('#msgsent_'+ json['rcvdorderid']).fadeIn('slow');
                $('#msgrcvd_' + json['rcvdorderid']).fadeIn('slow');
              });

              var transResult = "<p><strong>Result: </strong>"+ success_trans +" Successful, " + failed_trans +" Failed</p><p><strong>Total Captured: </strong> $" + total_captured + "</p><p><strong>Total Voided: </strong> $"+ total_voided +"</p><p><strong>Total Refunded: </strong> $" + total_refunded +"</p>";
              $('#trans-results').html(transResult);
              $('#trans-results').show();
            },
            error: function(xhr, ajaxOptions, thrownError){
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
          });
        }
      }
    }
  });

  $('button[id^=\'update-statuses-submit\']').on('click', function(e){
    e.preventDefault();
    if(!confirm('Are you sure you want to change the status of selected orders?')){return false;}
    var selected = $('input[name^=\'selected\']:checked');
    if(selected.length == 0){
      alert('Select Any Order To Proceed!');
      return false;
    }
    var update_statuses_dropdown_value  = $("#update-statuses :selected").val();
    var update_statuses_dropdown_name   = $("#update-statuses :selected").text();
    var store_url                       = "<?php echo $store; ?>";
    var username                        = $('input[name=\'username\']').val();
		var comment                         = "";
    for(i = 0; i < selected.length; i++){
      var orderId;
      if(orderId = $(selected[i]).parent().parent().find('input[name^=\'ppat_order_id\']').val()){	
        if($(selected[i]).parent().parent().find('input[name^=\'ppat_order_status_id\']').val() !== update_statuses_dropdown_value){
          $('#order_status_column_'+orderId).html(update_statuses_dropdown_name);
          $(selected[i]).parent().parent().find('input[name^=\'ppat_order_status_id\']').val(update_statuses_dropdown_value);
					$('#ppat_response_'+ orderId).html('<div class="success">Order status changed to ' + update_statuses_dropdown_name + ' successfully!</div>');
          $('#pp_resp_'+ orderId).fadeIn('slow');
          $.ajax({
				    url: store_url + 'index.php?route=api/order/history&token=' + token + '&order_id=' + orderId,
				    type: 'post',
				    dataType: 'json',
				    data: 'order_status_id=' + update_statuses_dropdown_value + '&sales_person=0&username=' + encodeURIComponent(username) + '&notify=0&override=0&append=0' + '&comment=' + comment,
						success: function(json) {
              setTimeout(function() { 
                $('.pp_resp').fadeOut('slow'); 
              }, 15000); 
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" +xhr.responseText);
						}
				  });
        }else{
          $('#ppat_response_'+ orderId).html('<div class="warning">This order is already in ' + update_statuses_dropdown_name + ' status. Status Change request of above order is denied!</div>');
          $('#pp_resp_'+ orderId).fadeIn('slow');
          setTimeout(function() { 
            $('.pp_resp').fadeOut('slow'); 
          }, 15000); 
        }
      } 
    }
  });

  $('button[id^=\'update-actions-submit\']').on('click', function(e){
    e.preventDefault();
    if(!confirm('Are you sure you want to change the actions of selected orders?')){return false;}
    var selected      = $('input[name^=\'selected\']:checked');
    if(selected.length == 0){
      alert('Select Any Order To Proceed!');
      return false;
    }
    var update_action_dropdown_value = $("#update-actions :selected").val();
    for(i = 0; i < selected.length; i++){
      var orderId;
      if(orderId = $(selected[i]).parent().parent().find('input[name^=\'ppat_order_id\']').val()){
        $("#ppat_action_" + orderId).val(update_action_dropdown_value).change();
      } 
    }
  });

  $("#update-actions").on('change', function(e){

    var updated_value = $("#update-actions").val();
    $(".ppat_action_class").val(updated_value);

  });
//--></script>
<script type="text/javascript"><!--
  $('input[name^=\'selected\']').on('change', function() {
    $('#batch-submit').prop('disabled', true);

    var selected = $('input[name^=\'selected\']:checked');

    for (i = 0; i < selected.length; i++) {
      if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
        $('#batch-submit').prop('disabled', false);
        break;
      }
    }
  });
  $('input[name^=\'selected\']:first').trigger('change');
//--></script>
<script type="text/javascript"><!--
  // Login to the API
  var token = '';
  $.ajax({
    url: '<?php echo $store; ?>index.php?route=api/login',
    type: 'post',
    data: 'key=<?php echo $api_key; ?>',
    dataType: 'json',
    crossDomain: true,
    success: function(json) {
      $('.alert').remove();
      if (json['error']) {
        if (json['error']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['ip']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
        }
      }
      if (json['token']) {
        token = json['token'];
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });

  $(document).delegate('#button-ip-add', 'click', function() {
    $.ajax({
      url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
      type: 'post',
      data: 'ip=<?php echo $api_ip; ?>',
      dataType: 'json',
      beforeSend: function() {
        $('#button-ip-add').button('loading');
      },
      complete: function() {
        $('#button-ip-add').button('reset');
      },
      success: function(json) {
        $('.alert').remove();

        if (json['error']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }

        if (json['success']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
//--></script></div>
<?php echo $footer; ?>