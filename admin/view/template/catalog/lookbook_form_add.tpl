<?php echo $header; ?><?php echo $column_left; ?>
<script src="view/javascript/jquery.fileuploadmulti.min.js"></script>
<script src="view/javascript/jquery-upload.js"></script>
<link href="view/css/uploadfilemulti.css" rel="stylesheet">
  <div id="content" style="padding-left: 250px;" >
      <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a></div>
                <h1><?php echo $heading_title; ?></h1>
              <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    
     
        </div>
      </div>
     
      
      <div class="container-fluid">
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