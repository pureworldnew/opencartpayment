<?php if(!isset($viewrma) AND $viewrma) return; ?>
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $heading_title; ?></title>
  <base href="<?php echo $base; ?>" />
  <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
  <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
  <link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
  <link href="catalog/view/theme/default/stylesheet/rma/rma.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="row">
      <?php $class = 'col-sm-12'; ?>
      <div id="content" class="<?php echo $class; ?>">
        <h1>
          <?php echo $heading_title; ?>
          <div class="pull-right">
            <button type="button" class="btn btn-primary" onclick="printinvoice();"><i class="fa fa-save"></i></button>
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

            <div class="row">
              <div class="col-sm-12">
                <p class="bg bg-primary"><i class="fa fa-wrench"></i> <?php echo $wk_viewrma_item_req; ?></p>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <tr>
                          <td class="text-left"><?php echo $wk_viewrma_pname; ?></td>
                          <td class="text-left"><?php echo $wk_viewrma_model; ?></td>
                          <td class="text-left"><?php echo $wk_viewrma_price; ?></td>
                          <td class="text-left"><?php echo $wk_viewrma_reason; ?></td>
                          <td class="text-left"><?php echo $wk_viewrma_qty; ?></td>
                          <td class="text-left"><?php echo $wk_viewrma_ret_qty; ?></td>
                          <td class="text-left"><?php echo $wk_viewrma_subtotal; ?></td>
                        </tr>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(isset($prodetails) AND $prodetails){
                        foreach($prodetails as $pdetails){ ?>
                          <tr>
                            <td class="text-left"><?php echo $pdetails['name']; ?></td>
                            <td class="text-left"><?php echo $pdetails['model']; ?></td>
                            <td class="text-left"><?php echo $pdetails['price']; ?></td>
                            <td class="text-left"><?php echo $pdetails['reason']; ?></td>
                            <td class="text-left"><?php echo $pdetails['ordered']; ?></td>
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
        </fieldset>
        </div>
      </div>
  </div>
</body>
</html>
<script>
function printinvoice(){
  window.print();
}
</script>
