<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

<div class="container">
<h1>Qr Code Generator</h1>
  <div class="row">
    <form action="<?php echo $action; ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
	
	  <div class="form-group">
	    <label class="control-label col-sm-2">Generate QrCode For Products</label>
		<div class="col-sm-10">
		  <input type="submit" class="btn btn-info" name="qrgenerator" value="Generate">
		</div>
	  </div>
	</form>
  </div>
  <h1>Export Products as CSV</h1>
  <div class="row">
    <form action="<?php echo $action_csv; ?>" class="form-horizontal" method="post" enctype="multipart/form-data">
	
	  <div class="form-group">
	    <label class="control-label col-sm-2">Export Products as CSV</label>
		<div class="col-sm-10">
		  <input type="submit" class="btn btn-info" name="exportcsv" value="Export">
		</div>
	  </div>
	</form>
  </div>
</div>

  </div>


<?php echo $footer; ?>