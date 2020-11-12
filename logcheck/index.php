<!DOCTYPE html>
<html lang="en">
<?php
require('header.php');

?>

<body>
<script>
    $( document ).ready(function() {
        window.col = -1;
        window.row = -1;
    
        
     var table = $("#maintable").DataTable({ 
        "processing":true,
        "serverSide": true,
        "ajax": 'server_processing.php'
       
     });
     //table.makeEditable();
     ////////////////////////////////////////////////////
       // table.columns().every( function () {
        //var that = this;
        $('input').on('click',function(e){
            //alert ("OK");
            e.stopPropagation();
            });
       
 
        $( '.mfilter' ).on( 'keyup change', function () {
            //alert (this.id);
            table.columns(this.id).search(this.value).draw();
        } );
   // } );
     /////////////////////////////////////////////////////   
     $('#maintable tbody').on( 'click', 'tr', function () {
        //alert ("OK");
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
  
     
     $('#editinfobutton').click( function () {
        var tdata = table.row('.selected').data();
        var itemno =  (tdata[2]);
        //alert(itemno);
        var ret = "";
        var to_write = "";
        //var clear = document.getElementById("res_data").rows.length;
        //alert (clear);
        
        //document.getElementById("ratesdatatable").innerHTML = "";

        $.get("db.php?action=Getres&olid="+itemno,function(data,val){
            //alert(data);
            ret = JSON.parse(data);

            document.getElementById("itemno").innerHTML = itemno;
            var t = document.getElementById("res_data");
            //alert(ret.cart_total);
            rowContent = "<table>";
            pObject(ret);
           
            rowContent += "</table>"
            t.innerHTML = rowContent;
            
           
            
        });
        
        $("#editdata").modal({
    backdrop: 'static',
    keyboard: false
    
});
    } );
     
    $('#deleteinfobutton').click( function () {
        table.row('.selected').remove().draw( false );
    } );
    });//document.ready ends



function pObject(ret)
{
    
    for(var key in ret){
                if (typeof ret[key] == "object")
                {
                   rowContent += pObject(ret[key])
                }else{
                rowContent += "<tr><td>" + key + "</td><td>" + ret[key] + "</td></tr>";
                }
            }
             //console.log(rowContent);
            return rowContent;
}
</script>

<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			 
            <br />
            <br />
            <br />
			<h3>
				LOG CHECK UTILITY
			</h3>
			<blockquote>
				<p>
					CHECK LOG
				</p> <small>View the added information in <cite>Table</cite></small>
			</blockquote> <button type="button" id="editinfobutton" class="btn btn-primary btn-default">VIEW DETAILS</button>
            
            <br /><br />
			<table class="table table-bordered table-hover table-condensed" id="maintable">
				<thead>
					<tr>
						
						<th style="text-align:center;">
							Order ID
						</th>
						<th style="text-align:center;">
							STEP
						</th>
                        <th style="text-align:center;">
							LOG ID
						</th>
						<th style="text-align:center;">
							DATE ADDED
						</th>
                        
                        
					</tr>
                    <tr class="danger">
                        
                        <td align="center"><input class="mfilter" type="text" id="0" style="width: 70px;"/></td>
                        <td align="center"><input class="mfilter" type="text" id="1" style="width: 240px;"/></td>
                        <td align="center"><input class="mfilter" type="text" id="2" style="width: 70px;"/></td>
                        <td align="center"><input class="mfilter" type="text" id="3" style="width: 70px;"/></td>
                       
                    </tr>
                    
				</thead>
				<tbody>
                    
                
				</tbody>
                
			</table>
			 
			<div class="modal" id="editdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Ã—</button>
                            <h4 class="modal-title" id="myModalLabel">
                                Edit Data (Item # <span id="itemno"></span>)
                            </h4>
                        </div>
                        <div class="modal-body">
                            
                            
                            
                            <table border="0" width="100%">
                            <tr id="heading">
                            <td colspan="2">Selected Items Parameters</td>
                            </tr>
                            </table>
                                <div style='padding: 3px; width: 800px; word-break: break-all; word-wrap: break-word;' id="res_data">{RES_DATA}</div>
                            
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
			
			
		</div>
	</div>
</div>
</body>
</html>
