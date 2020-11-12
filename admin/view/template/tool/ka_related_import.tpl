<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
        <div class="container-fluid">
            
                <h1><?php echo $heading_title; ?></h1>
     			<ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
        </div>
        	<?php if (isset($error_warning)) { ?>
          		<div class="warning"><?php echo $error_warning; ?></div>
          	<?php } ?>
          	<?php if (isset($success)) { ?>
          		<div class="success"><?php echo $success; ?></div>
         	<?php } ?>
            
            <?php if($error['text']!=''){ ?>
            <div class="<?php echo $error['type']; ?>"><?php echo $error['text']; ?></div>
        <?php } ?>
      </div>
   
        
        
        <div class="container-fluid">
            <span class="notification"></span>
            <div class="invalid-columns">
                <?php if(!empty($error['columns'])){ ?>
                    <div class="warning">! OOPS there are some columns names which are invalid.</div>
                    <?php
                    foreach($error['columns'] as $key=>$item){ ?>
                        <p><strong><?php echo $key+1; ?> : </strong><?php echo $item; ?></p>
                    <?php 
                    }?>
                    <h2>Here is list of valid columns please validate.</h2>
                    <?php
                    foreach($systemColumns as $key=>$item){ ?>
                        <p><strong><?php echo $key+1; ?> : </strong><?php echo $item; ?></p>
                    <?php 
                    }
                } ?>
            </div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td><span class="required">*</span>Select a CSV file to upload:</td>
                        <td>
                            <input type="file" name="fileToUpload" id="fileToUpload"/>
                        </td>
                        
                        
                        <td>
                            <button class="btn btn-info" type="button" id="upload" name="submit" value="Upload" />Upload </button>
                        </td>
                    </tr>
                </table>
            </form>
            <?php if($error['text']!='' && $error['type']=='success' && empty($error['columns'])){ ?>
                <p>CSV uploaded<p>
                <a href="javascript:void(0);" id="proceedConcat">Click here to proceed to Concatinate</a>
                <p>Concatination may take some time. Do not refresh or click on upload.</P>
            <?php } ?>
            <div id="resultConcat"></div>
        </div>
</div>
<script type="text/javascript">
$('#upload').on('click', function() {
    //alert("HEHEH");
    //return;
    var file_data = $('#fileToUpload').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('fileToUpload', file_data);
    //alert(form_data);   
    //return;                          
    $.ajax({
        url: 'index.php?route=tool/ka_product_import/impRelated&token=<?php echo $token; ?>', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        beforeSend: function ( xhr ) {
            $('.notification').html('<div class="alert alert-info"><i class="fa fa-refresh fa-spin"></i> Processing...</div>');
        },
        
        success: function(response){
            resp = JSON.parse(response);
            l = resp.msg.length;
            //console.log(l);
            msg = "";
            for (i = 0 ; i<l ; i++)
                msg += '<div class="alert alert-info"><i class="fa fa-check-circle"> '+ resp.msg[i] + '</i></div>'
            $('.notification').html(msg);
        }
     });
});
var ProductConcat={
    init:function(){
        $('.success,.warning').remove();
        this.wait('Running Step 2: Saving Temporary data.');
        this.parseCsvTemp();
    },
    parseCsvTemp:function(){
        var that=this;
        $.ajax({
            url:'index.php?route=productconcat/script&token=<?php echo $token; ?>',
            type:'post',
            success:function(data){
                that.updateResponse(data);
                that.concatProducts();
            }
        });
    },
    concatProducts:function(){
        var that=this;
        this.stopWait();
        this.wait('Running Step 3: Grouping Products');
        $.ajax({
            url:'index.php?route=productconcat/script/concat&token=<?php echo $token; ?>',
            type:'post',
            success:function(data){
                that.updateResponse(data);
                that.stopWait();
            }
        });
    },
    updateResponse:function(data){
        if(data!=''){
            data=JSON.parse(data)
            var html="<div class="+data.type+">"+data.text+"</div>";
            $('#resultConcat').append(html);
        }
    },
    stopWait:function(){
        $('#resultConcat').find('h2').remove();
    },
    wait:function(msg){
        $('#resultConcat').append('<h2>'+msg+'</h2>');
    }
};
$(function(){
    $('#proceedConcat').on('click',function(){
        ProductConcat.init($(this));
    });
});
</script>

<?php echo $footer; ?>