
<div id="content">
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="pull-right">
          <button  id="form-rma-btn" title="Save Return" class="btn btn-primary" onclick="createReturn();"><i class="fa fa-save"></i></button> 
          <a href="javascript:void(0);" title="<?php echo $button_back; ?>" class="btn btn-default" onclick="cancelReturn();"><i class="fa fa-reply"></i></a>
        </div>
        <form action="<?php echo $action; ?>" id="form-rma" method="post" enctype="multipart/form-data">

            <div class="form-group" id="filter_return_by">
              <label class="control-label" for="input-auth-info">Create Return By</label>
              <select class="form-control" name="return_by" id="return_by">
                      <option></option>
                      <option value="1">Select Customer</option>
                      <option value="2">Enter Order ID</option>
              </select>
            </div>
            <div class="form-group" style="display:none" id="filter_rma_customer">
              <label class="control-label" for="input-auth-info"><?php echo "Select Customer"; ?></label>
              <input type="text" name="filter_rma_customer" value="<?php echo $filter_name; ?>" placeholder="<?php echo 'Select a customer. Orders for that customer will be listed below'; ?>" id="input-name" class="form-control" />
            </div>
            <div class="form-group" style="display:none" id="filter_rma_order_id">
              <label class="control-label" for="select_order_id">Enter Order ID</label>
              <div class="input-group">
                  <input type="text" name="select_order_id" value="" placeholder="Enter Order ID" id="select_order_id" class="form-control" />
                  <span class="input-group-btn">
                    <a href="javascript:void(0)" class="btn btn-success" id="load-order">Load Order Data</a>
                  </span>
              </div><!-- /input-group -->
            </div>
            <div class="form-group required">
              <label for="input-order" class="control-label"><span data-toggle="tooltip" title="<?php echo $text_order_info; ?>"><?php echo $wk_addrma_order; ?></span></label>
                <select name="order" id="input-order" class="form-control">
                  <?php if($order_result){ ?>
                    <option value=""><?php echo $text_select_o; ?></option>
                    <?php foreach ($order_result as $order_res) { ?>
                      <?php if ($order_res['order_id'] == $order) { ?>
                        <option value="<?php echo $order_res['order_id']; ?>" selected># <?php echo $order_res['order_id'].' , '.$order_res['total']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $order_res['order_id']; ?>" ># <?php echo $order_res['order_id'].' , '.$order_res['total']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  <?php }else{  ?>
                      <option><?php echo $text_select_no_o; ?></option>
                  <?php } ?>
                </select>
            </div>
            <div class="form-group required">
              <label class="control-label"><?php echo $wk_addrma_itemorder; ?></label>
              <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <tr>
                            <td class="text-left hidden-sm hidden-xs"><?php echo $wk_addrma_product ;?></td>
                            <td class="text-left"><?php echo $wk_addrma_sku ;?></td>
                            <td class="text-left"><?php echo $wk_addrma_quantity ;?></td>
                            <td class="text-left"><input type="checkbox" onclick="changeAll(this);" /></td>
                            <td class="text-left"><?php echo $wk_addrma_reason; ?></td>
                            <td class="text-left"><?php echo $text_rma_quantity ;?></td>
                          </tr>
                        </tr>
                      </thead>
                      <tbody id="add_rma_here"></tbody>
                    </table>
                  </div>
            </div>
            <div class="form-group">
              <label class="control-label" for="input-images"><span data-toggle="tooltip" title="<?php echo $text_allowed_ex; ?>"><?php echo $wk_addrma_imagespro; ?></span></label>
                <label type="button" for="upload-file" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $text_upload_img ;?>"><i class="fa fa-picture-o"></i> </label>
                <input type="file" name="rma_file[]" id="upload-file" accept="image/*" multiple value="" style="display:none" >
                <input type="hidden" name="image_array">
                <div class="clearfix"></div>
                <div class="rma-images-div"></div>
            </div>
            <div class="form-group required">
              <label class="control-label" for="input-addinfo"><?php echo $wk_addrma_addinfo; ?></label>
                <textarea rows="4" name="info" class="form-control" id="input-addinfo"><?php echo $info; ?></textarea>
            </div>
            <div class="form-group required">
                <label class="control-label"><?php echo "Select Return Status"; ?></label>
                  <select name="wk_rma_admin_adminstatus" class="form-control">
                    <?php foreach($admin_status as $admin_st){ ?>
                      <option <?php if($admin_st['name'] == 'Awaiting Review'){ echo 'selected'; }?> value="<?php echo $admin_st['status_id']; ?>"><?php echo $admin_st['name']; ?></option>
                    <?php } ?>
                  </select>
            </div>
            <div class="form-group">
              <label class="control-label" for="input-auth-info"><?php echo $text_rmano_info; ?></label>
                <input type="text" name="autono" class="form-control" id="input-auth-info" value="<?php echo $autono; ?>">
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var flag_first_check = 0;
<?php if ($order) { ?>
  $(document).ready(function() {
    flag_first_check = 1;
    $('#input-order').trigger('change');
  });
<?php } ?>
function selectSelected() {
  if (flag_first_check) {
    <?php if($json_selected) { ?>
      var json_selected = JSON.parse(JSON.stringify(<?php echo $json_selected; ?>));
      $.each(json_selected, function(i,e){
        $.each(e, function(index, element){
          class_row = 'select_row' + i;
          switch(index) {
            case 'selected':
                $('.selectedCheck.' + class_row).trigger('click');
                break;
            case 'reason':
                $('.selectReason.' + class_row).val(element);
                break;
            case 'quantity':
                $('.selectNumber.' + class_row).val(element);
                break;
          }
        });
      });
    <?php } ?>
    flag_first_check = 0;
  }
}
function imageArrayRemove(thisthis){
  $(image_array).each(function(key,value){
    if(thisthis.id==value)
      image_array[key] = '';
  });
  $(thisthis).next('div.tooltip').remove();
  $(thisthis).remove();
  $('input[name=\'image_array\']').val(image_array);
}

function readURL(input) {
    if (input.files && input.files[0]) {
      image_array = Array();
      for (i = 0; i < input.files.length; i++) {
        var reader = new FileReader();
        reader.onload = function (e) {
          image_array[$('.rma-images-div img').length] = 'rma-img-'+$('.rma-images-div img').length;
          $(".rma-images-div").append('<img class="img-thumbnail rma-image" data-toggle="tooltip" title="Click To Remove" src="'+e.target.result+'" id="rma-img-'+$('.rma-images-div img').length+'" onclick="imageArrayRemove(this)"/>');
        }
        reader.readAsDataURL(input.files[i]);
      }
    }
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    $('input[name=\'image_array\']').val(image_array);
}

$("#upload-file").change(function(){
    $(".rma-images-div").html('');
    readURL(this);
});

$('.datetime').datetimepicker({
  pickTime: false
});

function clrfilter(){
  url = 'index.php?route=catalog/wk_customer_orders&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>';
  location = url;
}

function filter() {
  url = 'index.php?route=catalog/wk_customer_orders&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>';

  var filter_order = $('input[name=\'filter_order\']').val();

  if (filter_order) {
    url += '&filter_order=' + encodeURIComponent(filter_order);
  }

  location = url;
}

function changeAll(thisthis){
  $('input[name*=\'selected\']').prop('checked', thisthis.checked);
  if(thisthis.checked){
    $('.disableCheck').prop('disabled',false);
  }else{
    $('.disableCheck').prop('disabled',true);
  }
}

$('#return_by').change( function() {
  if(this.value == 1)
  {
    $("#filter_rma_customer").show();
    $("#filter_rma_order_id").hide();
    $("#filter_return_by").hide();
  }

  if(this.value == 2)
  {
    $("#filter_rma_order_id").show();
    $("#filter_rma_customer").hide();
    $("#filter_return_by").hide();
  }

});
$('#add_rma_here').on('click','.selectedCheck', function(){
  if(this.checked)
    $(this).parents('tr').find('.disableCheck').prop('disabled',false);
   else
    $(this).parents('tr').find('.disableCheck').prop('disabled',true);
})
$('#input-order').change(function(){

  val_order = $('#input-order').val();
  token = '<?php echo $token; ?>';
  $.ajax({
    type: 'POST',
    dataType: 'json',
    data: ({order : val_order }),
    url: "index.php?route=catalog/wk_customer_orders/getorder&customer_id=<?php echo isset($customer_id) ? (int)$customer_id : 0 ?>&token="+token,
    success: function(data){
      jQuery('#add_rma_here').html('');
      count = 0;
      $.each(data,function(index,element){
        if(element['product_id']!=undefined && element['product_id']!=''){
          html = '<tr><td class="text-left hidden-sm hidden-xs"><a target="_blank" href=index.php?route=product/product&product_id='+element['product_id']+'>'+element['name']+'</a>';
          if(element['option']!=undefined && element['option']!=''){
            for(j in element['option']) {
              html+= '<br />';
              html+= '&nbsp;<small> - ' + element['option'][j]['name'] + ':' + element['option'][j]['value'] + '</small>';
            }
          }
          html+= '</td>';
          html+= '<td class="text-left">'+element['model']+'</td>';
          html+= '<td class="text-left">'+element['quantity']+'</td>';
          html+= '<td class="text-left"><input type="checkbox" name="selected[]" value="'+element['product_id']+'" class="selectedCheck select_row' + count + '"></td>';
          html+= '<td class="text-left"><select name="reason[]" class="form-control selectReason disableCheck select_row' + count + '" disabled>';
          <?php if($reasons){ ?>
            <?php foreach($reasons as $reasonss){ ?>
              html+= '<option value="<?php echo $reasonss["id"]; ?>" ><?php echo $reasonss["reason"]; ?></option>';
            <?php } ?>
          <?php }else{ ?>
            html+= '<option value=""></option>';
          <?php } ?>
          html+= '</select></td>';
          html+= '<td class="text-left"><input type="number" name="quantity[]" value="" min=1 max="'+element['quantity']+'" class="form-control disableCheck selectNumber select_row' + count + '" disabled required><input type="hidden" name="product[]" value="'+element['order_product_id']+'" disabled class="disableCheck"><input type="hidden" name="json_select['+count+']" value="'+element['order_product_id']+'"></td></tr>';
          $('#add_rma_here').append(html);
          count++;
        }
      });
      selectSelected();
     },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log("Status: " + textStatus);
      console.log("Error: " + errorThrown);
    }
  });
});

$('#load-order').click(function(){

val_order = $('#select_order_id').val();
$('#input-order').val(val_order);
token = '<?php echo $token; ?>';
$.ajax({
  type: 'POST',
  dataType: 'json',
  data: ({order : val_order }),
  url: "index.php?route=catalog/wk_customer_orders/getorder&customer_id=<?php echo isset($customer_id) ? (int)$customer_id : 0 ?>&token="+token,
  success: function(data){
    jQuery('#add_rma_here').html('');
    count = 0;
    $.each(data,function(index,element){
      if(element['product_id']!=undefined && element['product_id']!=''){
        html = '<tr><td class="text-left hidden-sm hidden-xs"><a target="_blank" href=index.php?route=product/product&product_id='+element['product_id']+'>'+element['name']+'</a>';
        if(element['option']!=undefined && element['option']!=''){
          for(j in element['option']) {
            html+= '<br />';
            html+= '&nbsp;<small> - ' + element['option'][j]['name'] + ':' + element['option'][j]['value'] + '</small>';
          }
        }
        html+= '</td>';
        html+= '<td class="text-left">'+element['model']+'</td>';
        html+= '<td class="text-left">'+element['quantity']+'</td>';
        html+= '<td class="text-left"><input type="checkbox" name="selected[]" value="'+element['product_id']+'" class="selectedCheck select_row' + count + '"></td>';
        html+= '<td class="text-left"><select name="reason[]" class="form-control selectReason disableCheck select_row' + count + '" disabled>';
        <?php if($reasons){ ?>
          <?php foreach($reasons as $reasonss){ ?>
            html+= '<option value="<?php echo $reasonss["id"]; ?>" ><?php echo $reasonss["reason"]; ?></option>';
          <?php } ?>
        <?php }else{ ?>
          html+= '<option value=""></option>';
        <?php } ?>
        html+= '</select></td>';
        html+= '<td class="text-left"><input type="number" name="quantity[]" value="" min=1 max="'+element['quantity']+'" class="form-control disableCheck selectNumber select_row' + count + '" disabled required><input type="hidden" name="product[]" value="'+element['order_product_id']+'" disabled class="disableCheck"><input type="hidden" name="json_select['+count+']" value="'+element['order_product_id']+'"></td></tr>';
        $('#add_rma_here').append(html);
        count++;
      }
    });
    selectSelected();
   },
  error: function(XMLHttpRequest, textStatus, errorThrown) {
    console.log("Status: " + textStatus);
    console.log("Error: " + errorThrown);
  }
});
});

$('input[name=\'filter_rma_customer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=<?php echo $customer_link; ?>/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'] + '(' + item['email'] + ')',
            value: item['customer_id'],
            email: item['email']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_rma_customer\']').val(item['label']);
   // location = 'index.php?route=catalog/wk_customer_orders_pos&token=<?php echo $token; ?>&customer_id='+encodeURIComponent(item['value'])+'&customer_email=' + encodeURIComponent(item['email']);

    $('#tab_returns').load('index.php?route=catalog/wk_customer_orders_pos&token=<?php echo $token; ?>&customer_id='+encodeURIComponent(item['value'])+'&customer_email=' + encodeURIComponent(item['email']));
  }
});


/* function cancelReturn()
{
  var html = '<div class="container-fluid" style="margin-top: 25px;"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-list"></i> Returns List</h3></div><div class="panel-body">';
  
  html += '<input id="button-refresh" type="button" class="btn btn-primary" value="Refresh" onClick="refreshData()"/><button id="newReturn" class="pull-right btn btn-success" onClick="newReturnData()">Create New Return</button><br><br><div class="table-responsive"><table id="returnLists" class="table table-striped table-bordered" style="width:100%">';

  html += '<thead><tr role="row"><td style="width: 13px;" class="text-center sorting_asc" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-sort="ascending" aria-label=": activate to sort column descending"><input type="checkbox"></td><td class="text-right sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Return Type: activate to sort column ascending" style="width: 54px;">                    <a>Return Type</a></td><td class="text-right sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Order ID: activate to sort column ascending" style="width: 39px;"><a>Order ID</a></td><td class="text-left sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Customer: activate to sort column ascending" style="width: 66px;"><a>Customer</a></td><td class="text-left sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Product: activate to sort column ascending" style="width: 223px;"><a>Product</a></td><td class="text-left sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Product Model: activate to sort column ascending" style="width: 63px;"><a>Product Model</a></td><td class="text-left sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Returned Amount: activate to sort column ascending" style="width: 62px;">                    <a>Returned Amount</a></td><td class="text-left sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 43px;"><a>Status</a></td><td class="text-left sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Date Added: activate to sort column ascending" style="width: 49px;"><a>Date Added</a></td><td class="text-right sorting" tabindex="0" aria-controls="returnLists" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 44px;">Action</td></tr></thead>';

  html += '<tbody></tbody></table></div></div></div></div>';
  $("#tab_returns").html(html);
  refreshData();
} */

function cancelReturn()
  {
    $("#tab_backorders").html("");
		$("#tab_incoming_orders").html("");
		var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Loading Returns, Please Wait!</div>';
		$("#tab_returns").html(html);
		$('#tab_returns').load('index.php?route=sale/return&token=<?php echo $token; ?>'); 
  }

function newReturnData()
{
	var html = '<div style="margin-top:30px;text-align:center"><i class="fa fa-spinner fa-spin"></i> Please Wait!</div>';
	$("#tab_returns").html(html);
	$('#tab_returns').load('index.php?route=catalog/wk_customer_orders_pos&token=' + token); 
}


function createReturn()
{
	var html = '<i class="fa fa-spinner fa-spin"></i> Saving';
	$("#form-rma-btn").html(html);
	var form = $('#tab_returns').find("#form-rma");
	var url = form.attr('action');
	$.ajax({
		type: "POST",
		url: url,
		data: form.serialize(), // serializes the form's elements.
		success: function(data)
		{
			cancelReturn();
		}
	  });
}

function refreshData()
  {
  	$.ajax({

              url: "index.php?route=module/pos/getReturnData&token=<?php echo $token; ?>",
              type: 'post',
              dataType: 'html',
              
              beforeSend: function() {
                $('#button-refresh').button('loading');
              },
              complete: function() {
                $('#button-refresh').button('reset');
              },
              success: function(json) {
                $('#returnLists tbody').html(json);
                $('#returnLists').DataTable();
              },

              error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
            });
  }

</script>

<style>
.rma-image{
  max-width: 80px;
  max-height: 80px;
  margin-left: 15px;
  margin-bottom: 15px;
  cursor: pointer;
}
</style>
