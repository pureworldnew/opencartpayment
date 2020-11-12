<style>
.modal{
    /*display: block !important;*/
}
.sable{
      overflow-y: initial !important
}
.sablebody{
  height: 350px;
  overflow-y: auto;
}
</style>
<div class="modal" id="addinfo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Item Information (Retail Price : <span id="productrprice" style="color: red;">0</span>)
							</h4>
						</div>
						<div class="modal-body">
							<form name="frmone" >
                            <table border="0" width="100%">
                            <tr id="heading">
                            <td colspan="2">Product Particulars</td>
                            </tr>
                            <tr><td>
                                <label>Product ID</label><br />
                                <input type="text" name="productid" id="productid" required="required"/>
                                <input type="hidden" name="productprice" id="productprice" required="required" />
                                </td>
                            
                                <td>
                                <label for="productid">Product Name</label><br />
                                <input type="text" name="productname" id="productname" required="required" readonly="readonly" />
                                
                                </td></tr>
                                <tr id="heading">
                                <td colspan="2">Commission Entries</td>
                                </tr>
                                <tr><td>
                                <label for="productid">Minimum Price</label><br />
                                <input type="text" name="productmprice" id="productmprice" required="required" style="width:110px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optmprice" checked="checked">Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optmprice">Percentage
                                </label>
                                </td>
                                
                                <td>
                                <label for="productid">Acceptable Price</label><br />
                                <input type="text" name="productaprice" id="productaprice" required="required" style="width:110px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optaprice" checked="checked">Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optaprice">Percentage
                                </label>
                                </td></tr>
                                
                                <tr><td>
                                <label for="productid">Fixed Commission</label><br />
                                <input type="text" name="productcfprice" id="productfprice" required="required" style="width:110px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optfix" value="1" checked="checked">Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optfix" value="2">Percentage
                                </label>
                                </td>
                                
                                <td>
                                <label for="productid">Percentage Commission</label><br />
                                <input type="text" name="productcprice" id="productcprice" required="required" style="width:110px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optper" disabled="disable">Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optper" checked="checked">Percentage
                                </label>
                                </td></tr>
                                <tr>
                                <td>
                                <label for="productid">From Date</label><br />
                                <input type="text" name="newfromdate" id="newfromdate" required="required"/>
                                </td>
                                
                                <td>
                                <label for="productid">To Date</label><br />
                                <input type="text" name="newtodate" id="newtodate" required="required"/>
                                </td>
                                </tr>
                                </table>
                            </form>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="saveinfo" class="btn btn-primary">Save</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
            <!-- This dialog is for cook data-->
            
            <div class="modal" id="cookdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Process Data (Loaded Profile : CommissionData)
							</h4>
						</div>
						<div class="modal-body">
							<form name="frmone" >
                            <table border="0" width="100%">
                            <tr id="heading">
                            <td colspan="2">Cook Data Parameters</td>
                            </tr>
                            <tr>
                            <td colspan="2" align="left">
                            <label>Cook Type</label>
                            <select name="ctype" id="ctype">
                                <option value="0">Automatically</option>
                                <option value="1">Date Ranges</option>
                            </select>

                            </td>
                            
                            </tr>
                            <tr><td>
                                <label>Start Date</label><br />
                                <input type="text" name="sdate" id="sdate" required="required" disabled="disable"/>
                                <input type="hidden" name="edate1" id="edate1" required="required" disabled="disable"/>
                                </td>
                            
                                <td>
                                <label for="productid">End Date</label><br />
                                <input type="text" name="edate" id="edate" required="required" disabled="disable"/>
                                
                                </td></tr>
                                
                                <!--<tr id="heading">
                                <div id="logview" style="display: none;">
                                <td colspan="2">Log</td>
                                </tr>
                                <tr>
                                <td colspan="2">
                                    <textarea name="log" id="log" cols="85" rows="10"></textarea>
                                </td>
                                </div>
                                </tr> -->
                                
                                </table>
                            </form>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="cookinfo" class="btn btn-primary">Process It</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
            <!-- This dialog is for cook data DP-->
            
            <div class="modal" id="cookdataDP" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Process Data (Loaded Profile : DealPriceData)
							</h4>
						</div>
						<div class="modal-body">
							<form name="frmone" >
                            <table border="0" width="100%">
                            <tr id="heading">
                            <td colspan="2">Cook Data DP Parameters</td>
                            </tr>
                            <tr>
                            <td colspan="2" align="left">
                            <label>Cook Type</label>
                            <select name="ctypedp" id="ctypedp">
                                <option value="0">Automatically</option>
                                <option value="1">Date Ranges</option>
                            </select>

                            </td>
                            
                            </tr>
                            <tr><td>
                                <label>Start Date</label><br />
                                <input type="text" name="sdatedp" id="sdatedp" required="required" disabled="disable"/>
                                <input type="hidden" name="edate1" id="edate1" required="required" disabled="disable"/>
                                </td>
                            
                                <td>
                                <label for="productid">End Date</label><br />
                                <input type="text" name="edatedp" id="edatedp" required="required" disabled="disable"/>
                                
                                </td></tr>
                                
                                <!--<tr id="heading">
                                <div id="logview" style="display: none;">
                                <td colspan="2">Log</td>
                                </tr>
                                <tr>
                                <td colspan="2">
                                    <textarea name="log" id="log" cols="85" rows="10"></textarea>
                                </td>
                                </div>
                                </tr> -->
                                
                                </table>
                            </form>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="cookinfodp" class="btn btn-primary">Process It</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
            <!-- This dialog is for Progress Bar-->
            
            <div class="modal" id="progressbox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Processing Data.....Please wait
							</h4>
						</div>
						<div class="modal-body">
							<center><img src="./img/cooking.gif" width="70" height="70"></center>
						</div>
						<div class="modal-footer">
							 
						</div>
					</div>
					
				</div>
				
			</div>
            
            <!-- This dialog is for edit data-->
            
            <div class="modal" id="editdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Edit Data (Item # <span id="itemno"></span>)
							</h4>
						</div>
						<div class="modal-body">
							
                            <button id="addrow" class="btn btn-info btn-xs">Add row</button>
                            <button id="delrow" class="btn btn-info btn-xs">Delete Row</button>
                            <button id="postj"  class="btn btn-info btn-xs">Post Journal</button>
                            <br />
                            
                            <table border="0" width="100%">
                            <tr id="heading">
                            <td colspan="2">Selected Items Parameters</td>
                            </tr>
                                </table>
                                <table id="ratesdatatable" width="100%" border="1" >
                                <thead>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Item ID
                                    </th>
                                    <th>
                                        Min 
                                    </th>
                                    <th>
                                        Max 
                                    </th>
                                    <th>
                                        Commission
                                    </th>
                                    <th>
                                        Percentage
                                    </th>
                                    <th>
                                        Value Added
                                    </th>
                                    <th>
                                       From Date
                                    </th>
                                    <th>
                                       To Date
                                    </th>
                                </thead>
                                
                                <tbody>
                                    
                                </tbody>
                                
                                </table>
                            <div id="debug"></div>
						</div>
						<div class="modal-footer">
							 <button type="button" id="dclose" class="btn btn-primary">Save Journal</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
              <!-- This dialog is for edit data for Deal Price-->
            
            <div class="modal" id="fpeditdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Edit Data for Deal(s) (Selected Item # <span id="fpitemno"></span>)
							</h4>
						</div>
						<div class="modal-body">
							
                            <button id="fpaddrow" class="btn btn-info btn-xs">Add row</button>
                            <button id="fpdelrow" class="btn btn-info btn-xs">Delete Row</button>
                            <button id="fppostj"  class="btn btn-info btn-xs">Post Journal</button>
                            <br />
                            
                            <table border="0" width="100%">
                            <tr id="heading">
                            <td colspan="2">Selected Items Parameters</td>
                            </tr>
                                </table>
                                <table id="fpratesdatatable" width="100%" border="1" >
                                <thead>
                                    <th>
                                    <input type="checkbox" />
                                    </th>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Item ID
                                    </th>
                                    
                                    <th>
                                        Deal Qty 
                                    </th>
                                    <th>
                                        Deal Benefit
                                    </th>
                                    
                                    <th>
                                       From Date
                                    </th>
                                    <th>
                                       To Date
                                    </th>
                                </thead>
                                
                                <tbody>
                                    
                                </tbody>
                                
                                </table>
                            <div id="fpdebug"></div>
						</div>
						<div class="modal-footer">
							 <button type="button" id="dclose" class="btn btn-primary"  data-dismiss="modal">Close</button>
						</div>
					</div>
					
				</div>
				
			</div>
            <!-- This dialog is for set group values data-->
<div class="modal" id="setgroupdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Set Group Information
							</h4>
						</div>
						<div class="modal-body">
							<form name="frmone" >
                            <table border="0" width="100%">
                            
                                <tr id="heading">
                                <td colspan="2">Commission Entries</td>
                                </tr>
                                <tr><td>
                                <label for="productid">Minimum Price</label><br />
                                <input type="text" name="productmprice" id="pmprice" required="required" style="width:90px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optmprice" >Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optmprice" checked="checked">Percentage
                                </label>
                                </td>
                                
                                <td>
                                <label for="productid">Acceptable Price</label><br />
                                <input type="text" name="productaprice" id="paprice" required="required" style="width:90px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optaprice">Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optaprice" checked="checked">Percentage
                                </label>
                                </td></tr>
                                
                                <tr><td>
                                <label for="productid">Fixed Commission</label><br />
                                <input type="text" name="productcfprice" id="pfprice" required="required" style="width:90px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optfix" value="1">Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optfix" value="2" checked="checked">Percentage
                                </label>
                                </td>
                                
                                <td>
                                <label for="productid">Percentage Commission</label><br />
                                <input type="text" name="productcprice" id="pcprice" required="required" style="width:90px"/>
                                <label class="radio-inline">
                                    <input type="radio" name="optper" disabled="disable">Flat
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optper" checked="checked">Percentage
                                </label>
                                </td></tr>
                                <tr>
                                <td>
                                <label for="productid">From Date</label><br />
                                <input type="text" name="newfromdate" id="gfromdate" required="required"/>
                                </td>
                                
                                <td>
                                <label for="productid">To Date</label><br />
                                <input type="text" name="newtodate" id="gtodate" required="required"/>
                                </td>
                                </tr>
                                </table>
                            </form>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="btngenjournalfromgroup" class="btn btn-primary">Generate Journal</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
            <!-- This dialog is for set group values data-->
<div class="modal" id="setdpgroupdata" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Set Franchise Partner Group Rate Information
							</h4>
						</div>
						<div class="modal-body">
							<form name="frmone" >
                            <table border="0" width="100%">
                            
                                <tr id="heading">
                                <td colspan="2">Franchise Partner Entries</td>
                                </tr>
                                <tr>
                                <td>
                                <label for="productid">Select Partner</label><br />
                                <span id="SelCus"></span>
                                </td>
                                
                                
                                
                                <td>
                                <label for="productid">Agreement Percentage</label><br />
                                <input type="text" name="fpagperc" id="fpagperc" required="required" style="width:90px"/>
                                (For customer)
                                </td>
                                </tr>
                                <tr>
                                <td>
                                <label for="productid">Calculate On</label><br />
                                
                                <label class="radio-inline">
                                    <input type="radio" name="optdp" checked="checked">Unit Deal Price
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optdp">Retail Price
                                </label>
                                </td></tr>
                                <tr>
                                <td>
                                <label for="productid">From Date</label><br />
                                <input type="text" name="fpfromdate" id="fpfromdate" required="required"/>
                                </td>
                                
                                <td>
                                <label for="productid">To Date</label><br />
                                <input type="text" name="fptodate" id="fptodate" required="required"/>
                                </td>
                                </tr>
                                </table>
                            </form>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="btnfpgenjournalfromgroup" class="btn btn-primary">Generate Agreement</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
            
            <!-- addnewitemtogroup -->
<div class="modal" id="addnewitemtogroup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog sable" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Items to Group:( <span id="groupidview"></span>)
							</h4>
						</div>
						<div class="modal-body sablebody">
                        <table class="table table-bordered table-hover table-condensed">
							<thead>
                                <tr>
						<th width="2%">
                        <input type="checkbox" id="checkboxall" onclick="javascript:checkallitems()"/>
                        </th>
                        <th width="20%">
							Item Id
						</th>
						<th>
							Item Name
						</th>
						
					</tr>
                            </thead>
                            <tbody id="addnewiteminfotable">
                            
                            </tbody>
                        </table>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="savetogroup" class="btn btn-primary">Save</button>
						</div>
					</div>
					
				</div>
				
			</div>           
            
            <!-- This dialog is for add new journal-->
            
            <div class="modal" id="newjournal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Journal
							</h4>
						</div>
						<div class="modal-body">
                        <table>
                        <tr>
							<td><label>Journal Id: </label></td>
                            <td><input type="text" name="jid" id="jid" readonly="readonly" style="width:90px;text-align:center"/></td>
                        </tr>
                         <tr>
							<td><label>Journal Description: </label></td>
                            <td><input type="text" name="jdesc" id="jdesc" style="width:300px;text-align:left"/></td>
                        </tr>
                        </table>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="createjournal" class="btn btn-primary">Create & Next</button>
						</div>
					</div>
					
				</div>
				
			</div>

<!-- This dialog is for add new deal price journal-->
            
            <div class="modal" id="newdpjournal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Deal Price Journal
							</h4>
						</div>
						<div class="modal-body">
                        <table>
                        <tr>
							<td><label>Journal Id: </label></td>
                            <td><input type="text" name="dpjid" id="dpjid" readonly="readonly" style="width:90px;text-align:center"/></td>
                        </tr>
                         <tr>
							<td><label>Journal Description: </label></td>
                            <td><input type="text" name="dpjdesc" id="dpjdesc" style="width:300px;text-align:left"/></td>
                        </tr>
                        </table>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="createdpjournal" class="btn btn-primary">Create & Next</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
            <!-- This dialog is for cancle price journal-->
            
            <div class="modal" id="disablejournal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
							<h4 class="modal-title" id="myModalLabel">
								Disable Price Journal
							</h4>
						</div>
						<div class="modal-body">
                        <h4>Do you really want to disable Journal # <span id="djno" style="color:red">11111</span>? <br />This will cancle its effect to reports.</h4>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">No</button> <button type="button" id="disablejournal" class="btn btn-primary">Yes</button>
						</div>
					</div>
					
				</div>
				
			</div>

<!-- This dialog is for cancle deal price journal-->
            
            <div class="modal" id="disabledpjournal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
							<h4 class="modal-title" id="myModalLabel">
								Disable Deal Price Journal
							</h4>
						</div>
						<div class="modal-body">
                        <h4>Do you really want to disable Deal Price Journal # <span id="dpjno" style="color:red">11111</span>? <br />This will cancle its effect to reports.</h4>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="disabledpjournal" class="btn btn-primary">Disable Journal</button>
						</div>
					</div>
					
				</div>
				
			</div>




            
            <!-- This dialog is for add new item group-->
            
            <div class="modal" id="newitemgroup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Item Group
							</h4>
						</div>
						<div class="modal-body">
                        <table>
                        
                         <tr>
							<td><label>Group Name: </label></td>
                            <td><input type="text" name="gname" id="gname" style="width:300px;text-align:left"/></td>
                        </tr>
                        </table>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="creategroup" class="btn btn-primary">Create</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
   <!-- This dialog is for add new shop group-->
            
            <div class="modal" id="newshopgroup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Shop Group
							</h4>
						</div>
						<div class="modal-body">
                        <table>
                        
                         <tr>
							<td><label>Group Name: </label></td>
                            <td><input type="text" name="shopname" id="shopname" style="width:300px;text-align:left"/></td>
                        </tr>
                        </table>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="createshopgroup" class="btn btn-primary">Create</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
                        <!-- add new item to shop group -->
<div class="modal" id="addnewitemtoshopgroup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog sable" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Items to Shop Group:(<span id="shopgroupidview"></span>)
							</h4>
						</div>
						<div class="modal-body sablebody">
                        <table class="table table-bordered table-hover table-condensed">
							<thead>
                                <tr>
						<th width="2%">
                        <input type="checkbox" id="checkboxall" onclick="javascript:checkallitems()"/>
                        </th>
                        <th width="20%">
							Item Id
						</th>
						<th>
							Item Name
						</th>
						
					</tr>
                            </thead>
                            <tbody id="addnewitemshopinfotable">
                            
                            </tbody>
                        </table>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="savetoshopgroup" class="btn btn-primary">Save</button>
						</div>
					</div>
					
				</div>
				
			</div>           
            
            
  <!-- This dialog is for add new shop-->
            
            <div class="modal" id="newshop" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								Add New Shop
							</h4>
						</div>
						<div class="modal-body">
                        <table>
                        
                         <tr>
							<td><label>Shop ID: </label></td>
                            <td><input type="text" name="gname" id="txtnshopid" style="width:300px;text-align:left"/></td>
                        </tr>
                        
                        <tr>
							<td><label>Shop Name: </label></td>
                            <td><input type="text" name="gname" id="txtnshopname" style="width:300px;text-align:left"/></td>
                        </tr>
                        
                        <tr>
							<td><label>Shop Address: </label></td>
                            <td><input type="text" name="gname" id="txtnshopaddress" style="width:300px;text-align:left"/></td>
                        </tr>
                        
                        <tr>
							<td><label>Shop Phone#: </label></td>
                            <td><input type="text" name="gname" id="txtnshopno" style="width:300px;text-align:left"/></td>
                        </tr>
                        </table>
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" id="addshop" class="btn btn-primary">Add Shop</button>
						</div>
					</div>
					
				</div>
				
			</div>
            
            
            

