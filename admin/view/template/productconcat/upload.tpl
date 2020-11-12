<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
               <a onclick="$('#form').submit();" class="btn btn-primary">Upload</a>
            </div>
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
            <form action="" method="post" enctype="multipart/form-data" id="form">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td><span class="required">*</span>Select a CSV file to upload:</td>
                        <td>
                            <input type="file" name="concatCsvFile"/>
                            <input type="hidden" name="subPost" value="subPost"/>
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