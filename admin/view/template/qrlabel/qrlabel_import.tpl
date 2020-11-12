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
  
  <div class="container-fluid">
	<div class="alert notification" style="display:none;"><i class="fa fa-info-circle"></i>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    
  <form action="<?php echo $action_csv; ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
  <div class="">
    <div class="col-sm-3">
		<div class="form-group">
			<strong>Select File To start Import</strong>
		</div>
	</div> 
	<div class="col-sm-5">
		<div class="form-group">
			<input type="file" name="importfile"/>
		</div>
	</div> 
	<div class="col-sm-4">
		<div class="form-group">
			<input type="button" onClick="importP()" class="btn btn-info" name="exportcsv" value="Start Import">
		</div>
	</div> 
	
  </div>
  <div class="">
	  <div class="col-sm-4">
		  <div class="form-group">
			<input type="checkbox" value="pdf" name="filetype" id="filetype">Save Output in PDF format
		  </div>
	  </div>
	  <div class="col-sm-4">
		  <div class="form-group">
			<input type="checkbox" value="pdf" name="wmodel" id="wmodel">QR Code without Model
		  </div>
	  </div>
  </div>
  </form>
  </div>
  <div class="results" style="display:none">
    <div class="col-sm-4">
		<div class="form-group">
			<strong>File is Ready, Download File</strong>
		</div>
	</div> 
	<div class="col-sm-6">
		<div class="form-group">
			<input type="button" onClick="DownloadFile()" class="btn btn-lg btn-success" name="downloadfile" value="Download File">
			<input type="hidden" id="download_path" value=""/>
		</div>
	</div> 
  </div>
  
  <div class="">
	  <div class="col-sm-12">
		  
		  <label>Status</label>
		  <div id="status-box" name="status-box" class="status"></div>
	  </div>
  </div>
  
  </div>

  </div>

<script>

function upload() {
	var formData = new FormData();
	formData.append('importfile', $("input[name='importfile']")[0].files[0]);
	//formData.append('ispdf', $("#filetype").is(':checked'));

	$.ajax({
		   url : "index.php?route=qrlabel/qrlabel/impCSV&token=<?php echo $token; ?> ",
		   type : 'POST',
		   data : formData,
		   processData: false,  // tell jQuery not to process the data
		   contentType: false,  // tell jQuery not to set contentType
		   beforeSend: function (xhr) {
			   setStatus("Uploading File...");
			   //$("#sprogress").modal();
		   },
		   success : function(data) {
			   //console.log(data);
			   //alert(data);
			   $("#sprogress").modal('hide');
			   setStatus("File Uploaded");
			   setStatus("Starting");
			   //$("#status-box").html(getDate() + " Starting...");
			   startImport(data);
			   
		   }
	});
}

function importP() {
	box = $("input[name='importfile']");
	total = box[0].files.length;
	if (total > 0) {
		name = box[0].files[0].name;
		$("#status-box").html('');
		upload();
		return;
	}else{
		alert("No File Selected for Import");
		return;
	}
}

function DownloadFile() {
	file_path = $("#download_path").val();
	window.open(file_path);
}

function startImport(filepath) {
	var operation = "import";
	var d = new Date();
	ispdf = $("#filetype").is(':checked');
	wmodel = $("#wmodel").is(':checked');
	data = {filepath:filepath,ispdf:ispdf,wmodel:wmodel};
    $.ajax({
    url: 'index.php?route=qrlabel/qrlabel/generateQrFromCsv&token=<?php echo $token; ?>&v='+d.getTime(),
    type: 'post',
    data: data,
    dataType: 'json',
    
    beforeSend: function ( xhr ) {
	  $('.results').css('display','none');
      $('.notification').html('<div class="alert alert-info"><i class="fa fa-refresh fa-spin"></i> Processing...</div>');
    },
    complete: function() {
		$('.notification').css('display','block');
		$('.results').css('display','block');
        $('.notification').html('<div class="alert alert-success"><i class="fa fa-check-circle"> QRCODE Import Started </i><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        //location.reload();
        //interval = setInterval(chkbkprogress,2*1000);
        
    },    
    success: function(json) {
      if (json.code == 200){
		  $('.results').css('display','block');
		 setStatus("File generation completed. Ready to download: "+ json.file);
		 $("#download_path").val(json.file);
	  }
      console.log(json);
      console.log(json.code);
    },
    error: function(xhr, ajaxOptions, thrownError) {
                 console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    },
    timeout: 0
  });
}
function getDate() {
	var d = new Date();
	//var time = new Date();
	//dt = time.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
	var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds() + "." + d.getMilliseconds();
	return time;
}

function chkbkprogress() {
	
	html = $("#status-box").html();
	html += getDate() + " == " + getStatus() + "<br>";
	$("#status-box").html(html);
}

function setStatus(stext) {
	html = $("#status-box").html();
	html += getDate() + " == " + stext + "<br>";
	$("#status-box").html(html);
}

function getStatus() {
	return null;
}
</script>
<?php echo $footer; ?>
