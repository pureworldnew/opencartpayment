<?php if(!isset($viewrma) AND $viewrma) return; ?>
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

    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
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
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php } ?>
      <h1>
        <?php echo $heading_title; ?>
        <div class="pull-right">
          <a <?php echo $viewrma['shipping_label'] ? 'href="' . $print_shipping_lable . '"' : 'disabled'; ?> target="_blank" data-toggle="tooltip" class="btn btn-primary" title="<?php echo $text_print_lable; ?>" <?php echo $viewrma['shipping_label'] ? '' : 'disabled'; ?>> <i class="fa fa-truck"></i></a>
          <a href="<?php echo $print; ?>" target="_blank" data-toggle="tooltip" class="btn btn-primary" title="<?php echo $text_print; ?>"><i class="fa fa-print"></i></a>
          <a href="<?php echo $back; ?>" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_back; ?>"><i class="fa fa-reply"></i></a>
        </div>
      </h1>
        <fieldset>
          <legend><div class="col-sm-6 text-info"><i class="fa fa-user"></i> <?php echo $heading_title; ?></div><div class="col-sm-6 text-right text-info"><i class="fa fa-clock-o"></i> <?php echo $viewrma['date']; ?></div><div class="clearfix"></div></legend>
          <div class="row">
            <div class="col-sm-6">
                <p class="bg bg-primary"><i class="fa fa-circle-o-notch"></i> <?php echo $text_order_details; ?></p>
                <div class="bg">
                  <?php echo $wk_viewrma_orderid; ?>
                  <a href="<?php echo $viewrma['order_url']; ?>" target="_blank"># <?php echo $viewrma['order_id']; ?></a>
                </div>
            </div>

            <div class="col-sm-6">
                <p class="bg bg-primary"><i class="fa fa-eye"></i> <?php echo $wk_viewrma_status; ?></p>
                <div class="bg">
                  <?php echo $wk_viewrma_rma_tatus; ?>
                  <span style="color:<?php echo $viewrma['color']; ?>"><?php echo $viewrma['rma_status']; ?></span>  <br/>
                  <span><?php echo $wk_viewrma_authno; ?> </span><span class="auth_no_here">
                  <?php echo $viewrma['rma_auth_no']; ?></span>
                  <?php if(!$viewrma['rma_auth_no'] AND !$viewrma['cancel_rma'] AND !$viewrma['solve_rma']){ ?>
                    <label data-toggle="tooltip" title="<?php echo $wk_viewrma_enter_cncs_no ;?>" class="add-auth-no"><i class="fa fa-pencil"></i>
                    </label>

                    <div id="add-auth-no" class="alert alert-info hide">
                      <button type="button" class="close add-auth-no-close">&times;</button>
                      <div class="form-group">
                        <input type="text" id="auth-no-text" placeholder="<?php echo $wk_viewrma_enter_cncs_no; ?>" class="form-control" style="margin-bottom:3px;">
                      </div>
                      <button class="btn btn-info btn-xs submit-auth-no" value="Save"><i class="fa fa-save"></i></button>
                    </div>
                  <?php } ?>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <p class="bg bg-primary"><i class="fa fa-info-circle"></i> <?php echo $wk_viewrma_add_info; ?></p>
              <div class="bg"><?php echo $viewrma['add_info']; ?> </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <p class="bg bg-primary"><i class="fa fa-picture-o"></i> <?php echo $wk_viewrma_image; ?></p>
              <div class="bg">
                <label type="button" for="upload-file" class="btn btn-primary pull-right upload-file-label" data-toggle="tooltip" title="<?php echo $text_upload_img ;?>" <?php if($viewrma['cancel_rma'] || $viewrma['solve_rma']){ ?>disabled<?php } ?>><i class="fa fa-picture-o"></i> </label>
                <input type="file" name="rma_file[]" accept="image/*" multiple="" style="display:none" id="upload-file" >
                <div class="clearfix"></div>
                <br/>
                <ul class="thumbnails">
                <?php if($viewrma['images']){ ?>
                  <?php foreach($viewrma['images'] as $images){ ?>
                    <?php if($images['image']){ ?>
                      <li class="image-additional"><a href="<?php echo $images['image']; ?>" data-toggle="tooltip" title="View Images" class="thumbnail"><img src="<?php echo $images['resize']; ?>"></a></li>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
                </ul>
              </div>
            </div>
          </div>

          <?php if(!$viewrma['solve_rma']) { ?>
          <form action="<?php echo $action; ?>" id="cancel_return_form" method="post" enctype="multipart/form-data" class="form-horizontal hidden">

            <div class="row">
              <div class="col-sm-12">
                <p class="bg bg-primary"><?php echo $wk_viewrma_close_rma; ?></p>
                <div class="bg">
                  <input type="checkbox" id="cancel-request" name="solve" value="solve" required> <label for="cancel-request"><?php echo $wk_viewrma_close_rma_text; ?> </label>
                  <input type="submit" class="btn btn-info pull-right" value="Save">
                  <div class="clearfix"></div>
                  <input type="hidden" name="wk_viewrma_grp_id" value="<?php echo $vid; ?>">
                </div>
              </div>
            </div>

          </form>
          <?php } ?>

          <div class="row">
            <div class="col-sm-12">
              <p class="bg bg-primary"><i class="fa fa-wrench"></i> <?php echo $wk_viewrma_item_req; ?></p>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <tr>
                        <td class="text-left hidden-sm hidden-xs"><?php echo $wk_viewrma_pname; ?></td>
                        <td class="text-left"><?php echo $wk_viewrma_model; ?></td>
                        <td class="text-left"><?php echo $wk_viewrma_price; ?></td>
                        <td class="text-left hidden-sm hidden-xs" style="width:150px;"><?php echo $wk_viewrma_reason; ?></td>
                        <td class="text-left hidden-sm hidden-xs"><?php echo $wk_viewrma_qty; ?></td>
                        <td class="text-left"><?php echo $wk_viewrma_ret_qty; ?></td>
                        <td class="text-left"><?php echo $wk_viewrma_subtotal; ?></td>
                      </tr>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($prodetails) AND $prodetails){
                      foreach($prodetails as $pdetails){ ?>
                        <tr>
                          <td class="text-left hidden-sm hidden-xs"><a href="<?php echo $pdetails['link']; ?>" target="_blank"><?php echo $pdetails['name']; ?></a></td>
                          <td class="text-left"><?php echo $pdetails['model']; ?></td>
                          <td class="text-left"><?php echo $pdetails['price']; ?></td>
                          <td class="text-left hidden-sm hidden-xs"><?php echo $pdetails['reason']; ?></td>
                          <td class="text-left hidden-sm hidden-xs"><?php echo $pdetails['ordered']; ?></td>
                          <td class="text-left"><?php echo $pdetails['returned']; ?></td>
                          <td class="text-left"><?php echo $pdetails['total']; ?></td>
                        </tr>
                    <?php } }else{ ?>
                        <tr>
                          <td colspan="6" class="text-center">All Products Returned !! </td>
                        </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <br/>

          <?php if(isset($rma_messages) AND $rma_messages){ ?>

            <div class="row rma">
              <div class="col-sm-12">
                <p class="bg bg-primary"><i class="fa fa-comments-o"></i> <?php echo $text_messages; ?></p>
              </div>

            <?php foreach($rma_messages as $res_message){ ?>

              <?php if($res_message['writer']!='admin'){ ?>
                <div class="col-sm-10">
                  <div class="alert alert-success">
                    <label data-toggle="tooltip" title="<?php echo ucfirst($res_message['writer']); ?>"><i class="fa fa-user"></i> </label>
                    <label class="pull-right" ><i class="fa fa-clock-o"></i> <?php echo $res_message['date']; ?></label>
                    <br/>
                    <?php echo $res_message['message']; ?>
                    <?php if($res_message['attachment']){ ?>
                    <br/>
                      <a href="<?php echo $attachmentLink.$res_message['attachment'];?>" data-toggle="tooltip" title="<?php echo $text_download; ?>" class="text-info" target="_blank"><i class="fa fa-download"></i> <?php echo $res_message['attachment']; ?></a>
                    <?php } ?>
                  </div>
                </div>
              <?php }else{ ?>
                <div class="col-sm-10 pull-right">
                  <div class="alert alert-info">
                  <label data-toggle="tooltip" title="<?php echo ucfirst($res_message['writer']); ?>"><i class="fa fa-home"></i></label>
                  <label class="pull-right"><i class="fa fa-clock-o"></i> <?php echo $res_message['date']; ?></label>
                  <br/>
                  <?php echo $res_message['message']; ?>
                  <?php if($res_message['attachment']){ ?>
                  <br/>
                    <a href="<?php echo $attachmentLink.$res_message['attachment'];?>" target="_blank" data-toggle="tooltip" title="<?php echo $text_download; ?>" class="text-success"><i class="fa fa-download"></i> <?php echo $res_message['attachment']; ?></a>
                  <?php } ?>
                </div>
                </div>
              <?php } ?>
             <?php } ?>
            </div>


            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
            <br>
          <?php } ?>

          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form_msg">

            <div class="row required">
              <label class="col-sm-12 text-left control-label"><?php echo $wk_viewrma_msg; ?></label>
              <div class="col-sm-12">
                <input type="hidden" name="wk_viewrma_grp_id" value="<?php echo $vid; ?>">
                <textarea name="wk_view_message" rows="4" class="form-control" required><?php echo $wk_view_message; ?></textarea>

                <br/>

                <div class="input-group col-sm-12">
                  <span class="input-group-btn">
                    <label type="button" class="btn btn-primary" for="file-upload" data-toggle="tooltip" title="<?php echo $text_allowed_ex; ?>"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></label>
                  </span>
                  <input type="text" id="input-file-name" class="form-control" disabled/>
                </div>

                <input type="file" id="file-upload" name="up_file" class="form-control hide">
                <br/>
                <?php if($viewrma['cancel_rma']){ ?>
                  <input type="checkbox" id="re-open-cancel" name="wk_view_reopensolved" value="pending" style=" position: relative; top: 2px;" > <label class="text-left control-label" for="re-open-cancel"><?php echo $wk_viewrma_reopen; ?></label>
                <?php } ?>

                <button type="submit" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $wk_viewrma_msg; ?>"><i class="fa fa-save"></i> Save</button>
                <button type="button" id="cancel_return_btn" class="btn btn-warning pull-right" data-toggle="tooltip" title="Cancel Return"><i class="fa fa-close"></i> Cancel Return</button>

              </div>
            </div>
          </form>
      </fieldset>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
<?php echo $footer; ?>
<script>
    $("#cancel_return_btn").click(function(){
    if(confirm("Are you sure?")){
      $("#cancel_return_form").submit();
    }
  });
<?php if(!$viewrma['cancel_rma'] AND !$viewrma['solve_rma']){ ?>
jQuery(".submit-auth-no").click(function(){

  if(jQuery("#auth-no-text").val() != ""){
    jQuery("#auth-no-text").parent('div.form-group').removeClass('has-error');

    $('.submit-auth-no i').removeClass('fa-save').addClass('fa-spinner fa-spin');
    jQuery.ajax({
      url: "index.php?route=account/rma/rma/addcons",
      type: "POST",
      data: { auth_no : jQuery("#auth-no-text").val(),vid : "<?php echo $vid; ?>"},
      dataType: "json",
      success: function(content) {
        if(content['success']){
          jQuery(".auth_no_here").text(jQuery("#auth-no-text").val());
          jQuery("#add-auth-no").prepend("<span class='text-success'><?php echo $text_upload_success ; ?></span><br/>");
          $('#add-auth-no').slideUp(3000,function(){
            jQuery("#add-auth-no").remove();
          });
          $('i.fa.fa-pencil').remove();
        }else{
          alert('<?php echo $wk_viewrma_valid_no ; ?>');
        }
        $('.submit-auth-no i').removeClass('fa-spinner fa-spin').addClass('fa-save');
      }
    });
  }else{
    jQuery("#auth-no-text").parent('div.form-group').addClass('has-error');
  }
});

$('.add-auth-no').on('click', function(){
  $('#add-auth-no').removeClass('hide');
})
$('.add-auth-no-close').on('click', function(){
  $('#add-auth-no').addClass('hide');
})

jQuery("#upload-file").ajaxfileupload({
    "action"     : "index.php?route=account/rma/viewrma/imageupload",
    "params"     : {id : '<?php echo $vid; ?>'},
    "onComplete" :  function(json) {
          json = JSON.parse(json);
          if (json['error']) {
            $('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
  					$('html, body').animate({ scrollTop: 0 }, 'slow');
          } else {
            if (json['success']) {
              html = '';
              $.each(json['success'],function(i,e){
                html += "<li class='image-additional'><a href=" + e + " data-toggle='tooltip' title='View Images' class='thumbnail'><img src=" + e + " /></a></li>";
              })
              $('ul.thumbnails').append(html);
            }

          }
          $('.upload-file-label i').removeClass('fa-spinner fa-spin').addClass('fa-picture-o');

        },
    "onStart"    :  function() {
          $('.alert.alert-danger').remove();
          $('.upload-file-label i').removeClass('fa-picture-o').addClass('fa-spinner fa-spin');
        }
});
<?php } ?>
jQuery('input[name=up_file]').change(function(){
  $('#input-file-name').val(jQuery(this).val().replace(/C:\\fakepath\\/i, ''));
});

$(document).ready(function() {
  $('.thumbnails').magnificPopup({
    type:'image',
    delegate: 'a',
    gallery: {
      enabled:true
    }
  });
});
</script>
