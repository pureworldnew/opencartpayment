<?php echo $header; ?>
<script src="view/javascript/jquery.fileuploadmulti.min.js"></script>
<script src="view/javascript/jquery-upload.js"></script>
<link href="view/css/uploadfilemulti.css" rel="stylesheet">

<div id="content">
  
  
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  
  
<div class="box">
    <div class="heading">
     <h1><?php echo $heading_title; ?></h1>

  <div class="buttons">
         <button type="button" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger" onclick=""><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="" style="text-decoration:none;"><?php echo $button_cancel; ?></a></button>
 </div>
  
    </div>
  </div>
  <div class="content">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_upload; ?></h3>
      </div>
      <div class="panel-body">
	          <div id="mulitplefileuploader">Upload</div>
             <div id="status" ></div>
	        </div>

    </div>
  </div>
   

  <input type="hidden" id="upload_link" name="upload_link"  value="<?php echo $upload; ?>"  />
  <input type="hidden" id="txt_go_back" name="txt_go_back" value="<?php echo $text_go_back; ?>"  />
<?php echo $footer; ?>