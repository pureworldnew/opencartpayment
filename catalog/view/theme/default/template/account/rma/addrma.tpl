<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
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
      <h1><?php echo $heading_title; ?>
        <div class="pull-right">
          <a href="<?php echo $back; ?>" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_back; ?>"><i class="fa fa-reply"></i></a>
        </div></h1>
        <fieldset>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group required">
              <label class="col-sm-12 text-left control-label" for="input-order"><span class="modal-button" data-value="<?php echo $text_order_info; ?>"><?php echo $text_rma_order; ?></span></label>
              <div class="col-sm-12">
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
              <label class="col-sm-12 text-left control-label"><?php echo $text_rma_itemorder; ?></label>
              <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <tr>
                            <td class="text-left">Select to Return</td>
                            <td class="text-left"><?php echo $text_rma_product ;?></td>
                            <td class="text-left"><?php echo $text_rma_sku ;?></td>
                            <td class="text-left"><?php echo $text_rma_quantity ;?></td>
                            <td class="text-left"><?php echo $text_rma_reason; ?></td>
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
              <label class="col-sm-12 text-left control-label" for="input-images"><span class="modal-button" data-value="<?php echo $text_allowed_ex; ?>"><?php echo $text_rma_imagespro; ?></span></label>
              <div class="col-sm-12">
                <label type="button" for="upload-file" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $text_upload_img ;?>"><i class="fa fa-picture-o"></i> </label>
                <input type="file" name="rma_file[]" id="upload-file" accept="image/*" multiple value="" style="display:none" >
                <input type="hidden" name="image_array">
                <div class="clearfix"></div>
                <div class="rma-images-div"></div>

              </div>
            </div>

            <div class="form-group required">
              <label class="col-sm-12 text-left control-label" for="input-addinfo"><?php echo $text_rma_addinfo; ?></label>
              <div class="col-sm-12">
                <textarea rows="4" name="info" class="form-control" id="input-addinfo"><?php echo $info; ?></textarea>
              </div>
            </div>

<!--
            <div class="form-group">
              <label class="col-sm-12 text-left control-label" for="input-auth-info"><?php echo $text_rmano_info; ?></label>
              <div class="col-sm-12">
                <input type="text" name="autono" class="form-control" id="input-auth-info" value="<?php echo $autono; ?>">
              </div>
            </div>
-->
            <div class="form-group required">
              <?php if ($text_agree) { ?>
                <div class="col-sm-12 clearfix">
                  <?php if ($agree) { ?>
                    <input type="checkbox" name="agree" value="1" checked="checked"  id="input-agree" class="pull-left" />
                  <?php } else { ?>
                    <input type="checkbox" name="agree" value="1" id="input-agree" class="pull-left" />
                  <?php } ?>
                  <label class="control-label" for="input-agree"><?php echo $text_agree; ?></label>
                </div>
              <?php } ?>
            </div>

            <div class="buttons clearfix">
              <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
              <div class="pull-right">
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
              </div>
            </div>

        </form>
      </fieldset>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>



<div id="helpmodal" title="Help">
  <p class="msg"></p>
</div>
<?php echo $footer; ?>
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

function filter() {
  url = 'index.php?route=account/rma/addrma';

  var filter_order = $('input[name=\'filter_order\']').val();

  if (filter_order) {
    url += '&filter_order=' + encodeURIComponent(filter_order);
  }

  var filter_model = $('input[name=\'filter_model\']').val();

  if (filter_model) {
    url += '&filter_model=' + encodeURIComponent(filter_model);
  }

  var filter_date = $('input[name=\'filter_date\']').val();

  if (filter_date) {
    url += '&filter_date=' + encodeURIComponent(filter_date);
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

jQuery('.modal-button').bind('click', function() {
//    alert ("Clicked");
    var thisProp = $(this).data('value');
//    alert (thisProp);
    jQuery( "#helpmodal .msg" ).html( thisProp );
    $( "#helpmodal" ).dialog();
});

jQuery('#add_rma_here').on('click','.selectedCheck', function(){
  if(this.checked)
    $(this).parents('tr').find('.disableCheck').prop('disabled',false);
   else
    $(this).parents('tr').find('.disableCheck').prop('disabled',true);
})

jQuery('#input-order').change(function(){
  val_order = jQuery('#input-order').val();

  jQuery.ajax({
    type: 'POST',
    dataType: 'json',
    data: ({order : val_order }),
    url: "index.php?route=account/rma/rma/getorder",
    success: function(data){
      jQuery('#add_rma_here').html('');
      count = 0;
      $.each(data,function(index,element){
        if(element['product_id']!=undefined && element['product_id']!=''){
          html = '<tr>';
          html+= '<td class="text-left"><input type="checkbox" name="selected[]" value="'+element['product_id']+'" class="selectedCheck select_row' + count + '"></td>';
          html+= '<td class="text-left"><a target="_blank" href=index.php?route=product/product&product_id='+element['product_id']+'>'+element['name']+'</a>';
          if(element['option']!=undefined && element['option']!=''){
            for(j in element['option']) {
              html+= '<br />';
              html+= '&nbsp;<small> - ' + element['option'][j]['name'] + ':' + element['option'][j]['value'] + '</small>';
            }
          }
          html+= '</td>';
          html+= '<td class="text-left">'+element['model']+'</td>';
          html+= '<td class="text-left">'+element['quantity']+'</td>';
          html+= '<td class="text-left"><select name="reason[]" class="form-control selectReason disableCheck select_row' + count + '" disabled>';
          <?php if($reasons){ ?>
            <?php foreach($reasons as $reasonss){ ?>
              html+= '<option value="<?php echo $reasonss["id"]; ?>" ><?php echo $reasonss["reason"]; ?></option>';
            <?php } ?>
          <?php }else{ ?>
            html+= '<option value=""></option>';
          <?php } ?>
          html+= '</select></td>';
          html+= '<td class="text-left"><input type="number" name="quantity[]" value="" min=1 max="'+element['quantity_supplied']+'" class="form-control disableCheck selectNumber select_row' + count + '" disabled required><input type="hidden" name="product[]" value="'+element['order_product_id']+'" disabled class="disableCheck"><input type="hidden" name="json_select['+count+']" value="'+element['order_product_id']+'"></td></tr>';
          $('#add_rma_here').append(html);
          count++;
        }
      });
      selectSelected();
     }
 });

})
function hideoverlay(){
  $("#wk_rs_overlay").fadeOut(1000);
}
</script>
