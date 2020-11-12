<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-rma" data-toggle="tooltip" title="<?php echo $button_continue; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" id="form-rma" method="post" enctype="multipart/form-data" class="form-horizontal">
            <input type="hidden" id="param_order_id" value="<?php if(!empty($_GET['order_id'])) { echo $_GET['order_id']; } else { echo '0'; } ?>">
            <div class="form-group required">
              <label class="col-sm-2" for="input-order"><span data-toggle="tooltip" title="<?php echo $text_order_info; ?>"><?php echo $wk_addrma_order; ?></span></label>
              <div class="col-sm-10">
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
            </div>

            <div class="form-group required">
              <label class="col-sm-2"><?php echo $wk_addrma_itemorder; ?></label>
              <div class="col-sm-10">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <tr>
                            <td class="text-left"><?php echo $wk_addrma_product ;?></td>
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
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="input-images"><span data-toggle="tooltip" title="<?php echo $text_allowed_ex; ?>"><?php echo $wk_addrma_imagespro; ?></span></label>
              <div class="col-sm-10">
                <label type="button" for="upload-file" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $text_upload_img ;?>"><i class="fa fa-picture-o"></i> </label>
                <input type="file" name="rma_file[]" id="upload-file" accept="image/*" multiple value="" style="display:none" >
                <input type="hidden" name="image_array">
                <div class="clearfix"></div>
                <div class="rma-images-div"></div>

              </div>
            </div>

            <div class="form-group required">
              <label class="col-sm-2 text-left control-label" for="input-addinfo"><?php echo $wk_addrma_addinfo; ?></label>
              <div class="col-sm-10">
                <textarea rows="4" name="info" class="form-control" id="input-addinfo"><?php echo $info; ?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 text-left control-label" for="input-auth-info"><?php echo $text_rmano_info; ?></label>
              <div class="col-sm-10">
                <input type="text" name="autono" class="form-control" id="input-auth-info" value="<?php echo $autono; ?>">
              </div>
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
          html = '<tr><td class="text-left"><a target="_blank" href=index.php?route=product/product&product_id='+element['product_id']+'>'+element['name']+'</a>';
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
})

var param_order_id = $("#param_order_id").val();
param_order_id = parseInt(param_order_id);
if(isNaN(param_order_id))
{
  param_order_id = 0;
}
if(param_order_id > 0)
{
  $('#input-order').val(param_order_id);
  $('#input-order').trigger("change"); 
} 
</script>
<?php echo $footer; ?>
<style>
.rma-image{
  max-width: 80px;
  max-height: 80px;
  margin-left: 15px;
  margin-bottom: 15px;
  cursor: pointer;
}
</style>
