<?php echo $header;?><?php echo $column_left; ?>
<div id="content">

<div class="page-header">
        <div class="container-fluid">
            <?php if($data['page'] == 'step1'){ ?>
                <div class="pull-right">
                    
                  
                   <a id="next" class="btn btn-primary"><?php echo 'Next'; ?></a>
                  
                </div>
            <?php }else{?>
            	
               <div class="pull-right">
                    
                  
                   <a onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_export; ?></a>
                  
                </div>

            
            <?php }?>
            
            
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
      </div>
      
       <div class="container-fluid">
            <form action="<?php echo $download; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td><?php echo $entry_date_from;?></td>
                        <td>
                        
                        	  <div class="form-group">
				                <div class="input-group date">
				                  <input type="text" name="date_from" value="<?php echo $date_from; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
				                  <span class="input-group-btn">
				                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
				                  </span></div>
				              </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_date_to;?></td>
                        <td>
                        	
                        	<div class="form-group">
				                <div class="input-group date">
				                  <input type="text" name="date_to" value="<?php echo $date_to; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
				                  <span class="input-group-btn">
				                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
				                  </span></div>
				              </div>
                        	
                        </td>
                    </tr>
                </table>
            </form>
        </div>
   		<div class="container-fluid">
     		 <div class="progress" style="display:none;">
          		<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:4%" id="progress-bar">
        			4% Complete (success)
          		</div>
        </div>
        
        <div class="container-fluid">
          		<div class="pull-right">
        			
                   <?php 
                   
                   		if($export_button == '1'){             
                     		
                        	$style = '';
                        ?>
                        	
                            <strong style="font-size:16px;">Last save Export</strong>
                            <br>
                            <p>
                            	<label>Date From :</label><?php echo $export_dates['first'];?>
                            <br>
                            	<label>Date To :</label><?php echo $export_dates['last'];?>
                        	</p>
                        
                        <?php
                        }else{
                        	
                            $style = 'style=" display:none"';
                        
                        }   
                        
                        
                   ?>
                  	
                   <a id="exportbutton"  <?php echo $style;?> onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_export; ?></a>
                  
                </div>
        </div>
		
       	</div>
    </div>
</div>
<!--<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> -->
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
    $('.datetime').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'h:m'
    });
    //$('.time').timepicker({timeFormat: 'h:m'});
	 $('#next').bind('click', function() {
		$.ajax({
            url: 'index.php?route=sale/account/calculateDays&token=<?php echo $token;?>',
            beforeSend: function() {
                $("#exportbutton").hide();
            },
            complete: function() {
               
            },
            type: 'post',
            dataType: 'json',
            data: {
                "date_from": $('input[name="date_from"]').val(),
                "date_to": $('input[name="date_to"]').val(),
            },
            success: function(resp) {
            	$('#progress-bar').css('width',0+'%');
      			$('#progress-bar').attr('aria-valuenow',4);
			    $('#progress-bar').html(0+'% Complete (success)');
				init_exort(0,resp.weeks,0,resp.progress);
				
			}
        });
	});
	
	function init_exort(weeknumber,numberofweeks,currentprogress,progressperloop){
		
		$.ajax({
            url: 'index.php?route=sale/account/init&token=<?php echo $token;?>',
            beforeSend: function() {
                $(".progress").show();
				$("#exportbutton").hide();
            },
            complete: function() {
               // $(".progress").hide();
            },
            type: 'post',
            dataType: 'json',
            data: {
                "date_from": $('input[name="date_from"]').val(),
                "date_to": $('input[name="date_to"]').val(),
				"weeknumber": weeknumber,
            },
            success: function(resp) {
					currentprogress = parseFloat(currentprogress) + parseFloat(progressperloop);
					$('#progress-bar').css('width',currentprogress.toFixed(2)+'%');
      				$('#progress-bar').attr('aria-valuenow',currentprogress.toFixed(2));
			        $('#progress-bar').html(currentprogress.toFixed(2)+'% Complete (success)');
					weeknumber = weeknumber +1;
					numberofweeks = numberofweeks -1;
					
					if(numberofweeks >0){
						init_exort(weeknumber,numberofweeks,currentprogress,progressperloop);		
					}else{
						$("#exportbutton").show();
					}
			}
        });
		
		
	}
//--></script> 
<?php echo $footer;?>