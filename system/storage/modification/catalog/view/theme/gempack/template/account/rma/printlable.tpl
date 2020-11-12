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
  <style>
  fieldset {
    border: 1px dotted;
    padding: 50px;
  }
  @media (max-width: 767px) {
    /*fieldset {
      padding: 10px;
    }
    .col-sm-6{
      width: 100%;
      text-align: center !important;
    }*/
  }
  @media print{
    button{
      display: none;
    }
    .col-sm-6{
      min-width: 200px;
      min-height: 300px;
    }
    .table{
      border: 1px solid;
      border-spacing: 0;
      width: 100%;
    }
    .table td{
      min-width: 100px;
      border:1px solid;
      border-collapse: collapse;
      padding: 10px;
    }
  }
  </style>
</head>
<body>
  <div class="container dashboard">
    <div class="row">
      <?php $class = 'col-sm-12'; ?>    
      <div id="content" class="<?php echo $class; ?>">
        <br/><br/>
        <h1>
          <?php echo $heading_title; ?>
          <div class="pull-right">
            <button type="button" class="btn btn-primary" onclick="printinvoice();"><i class="fa fa-save"></i></button> 
          </div>
        </h1>
        <fieldset>
          <div class="col-sm-6 pull-left">
            <h3><?php echo $text_from; ?></h3>
            <div> 
              ------------------------------------------------------------------</br>
              ------------------------------------------------------------------</br>
              ------------------------------------------------------------------</br>
              ------------------------------------------------------------------</br>             
            </div>
          </div>

          <div class="col-sm-6 pull-right text-right">
            <h2><?php echo $text_shipping_label; ?></h2>
            <div> 
              <?php if($label){ ?>
                <img src="<?php echo $label; ?>" />
              <?php } ?>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6 pull-left">
            <h2><?php echo $text_to; ?></h2>
            <div> 
              <h4><?php echo nl2br($address); ?></h4>
            </div>
          </div>
        </fieldset>

        <br/><br/>

        <h1>
          <?php echo $text_auth_label; ?>
        </h1>
        <fieldset>
        <div class="table-responsive">
          <h4 class="text-center">
            <b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?> &nbsp;&nbsp;&nbsp;
            <b><?php echo $text_rma_id; ?></b> <?php echo $rma_id; ?></h4>          
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td><?php echo $text_product_name; ?></td>
                <td><?php echo $text_quantity; ?></td>
                <td><?php echo $text_reason; ?></td>
              </tr>
            </thead>
            <?php foreach ($prodetails as $key => $product) { ?>                
              <tr>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['returned']; ?></td>
                <td><?php echo $product['reason']; ?></td>
              </tr>
            <?php } ?>
          </table>
        </div>
        </fieldset>

        <br/><br/>

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