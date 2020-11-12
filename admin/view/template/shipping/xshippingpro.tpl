<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
 <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-auspost" value="save" name="action" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a id="quick_save" onclick="return false;" data-toggle="tooltip" title="<?php echo $button_save_continue; ?>" id="quick_save" class="btn btn-info"><i class="fa fa-clipboard"></i></a>&nbsp;
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
          </div>
          <div class="panel-body">
           <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-auspost" class="form-horizontal">
             <input type="file" class="import-csv" accept="text/csv" name="file" />
             <div class="row">
                    <div class="col-sm-2">
                      <ul id="method-list" class="nav nav-pills nav-stacked">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <?php
                            $xshippingpro['name']=(isset($xshippingpro['name']) && is_array($xshippingpro['name']))?$xshippingpro['name']:array();
                            foreach($xshippingpro['name'] as $no_of_tab=>$names){
                              if (!is_array($names))$names=array();
                              if (!isset($names[$language_id]) || !$names[$language_id]) {
                                 $names[$language_id]='Untitled Method '.$no_of_tab;
                               }
                               ?>
                          <li><a class="tab<?php echo $no_of_tab;?>" href="#shipping-<?php echo $no_of_tab; ?>"  data-toggle="tab"><?php echo $names[$language_id];?></a></li>
                        <?php } ?>
                      </ul>
                      
                      <button class="btn btn-success add-new" data-toggle="tooltip" form="form-auspost" type="button"  data-placement="bottom"  data-original-title="<?php echo $text_add_new_method?>"><i class="fa fa-plus"></i></button>
                    </div>
	                
                  <div class="col-sm-10">
                    <div id="shipping-container" class="tab-content">
                     <div class="tab-pane active" id="tab-general">
                        <ul class="nav nav-tabs" id="language-heading">
                            <?php $active_class=''; foreach ($languages as $language) { ?>
                             <li <?php if(!$active_class) echo 'active'; $active_class='1'; ?>><a href="#language<?php echo $language['language_id']; ?>heading" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                            <?php } ?>
                         </ul>
                         
                         <div class="tab-content">
                            <?php $active_class=''; foreach ($languages as $language) { ?>
                              <div class="tab-pane<?php if(!$active_class) echo ' active'; $active_class='1'; ?>" id="language<?php echo $language['language_id']; ?>heading">                         
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="lang-heading<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $tip_heading; ?>"><?php echo $text_heading; ?> </span></label>
                                    <div class="col-sm-10">
                                      <input type="text" name="xshippingpro_heading[<?php echo $language['language_id']; ?>]" value="<?php echo isset($xshippingpro_heading[$language['language_id']])?$xshippingpro_heading[$language['language_id']]:'Shipping Options'; ?>" placeholder="<?php echo $text_heading; ?>" id="lang-heading<?php echo $language['language_id']; ?>" class="form-control" />
                                    </div>
                                  </div>
                               </div> 
                            <?php } ?>
                          </div>
                         
                          <ul class="nav nav-tabs">
                             <li class="active"><a href="#global-general" data-toggle="tab"><?php echo $tab_general_general?></a></li>
                             <li><a href="#global-group" data-toggle="tab"><?php echo $tab_general_global?></a></li>
                             <li><a href="#global-export" data-toggle="tab"><?php echo $tab_import_export?></a></li>
                          </ul>
                         
                         <div class="tab-content"> 
                           <div class="tab-pane active" id="global-general">
                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-sort-order"><span data-toggle="tooltip" title="<?php echo $tip_sorting_global; ?>"><?php echo $entry_sort_order; ?> </span></label>
                              <div class="col-sm-10">
                                <input type="text" name="xshippingpro_sort_order" value="<?php echo $xshippingpro_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                              </div>
                           </div>
                          <div class="form-group">
                            <label for="xshippingpro_desc_mail" class="col-sm-2 control-label"><?php echo $text_description; ?> </label>
                             <div class="col-sm-10">
                                 <input <?php if(isset($xshippingpro_desc_mail) && $xshippingpro_desc_mail) echo 'checked';?> type="checkbox" name="xshippingpro_desc_mail" value="1" id="xshippingpro_desc_mail" />
                              </div>
                            </div>
                           <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-group-sorting"><span data-toggle="tooltip" title="<?php echo $tip_text_sort_type; ?>"><?php echo $text_sort_type; ?></span></label>
                            <div class="col-sm-10">
                              <select name="xshippingpro_sorting" id="input-group-sorting" class="form-control">
                               		 <?php
                   
                  					 foreach($sort_options as $sort_option_key=>$sort_option_value){
                    					$selected=(isset($xshippingpro_sorting) && $xshippingpro_sorting==$sort_option_key)?'selected':'';
                  					?>
                                   <option value="<?php echo $sort_option_key?>" <?php echo $selected?>><?php echo $sort_option_value?></option>
                                  <?php } ?>
                              </select>
                             </div>
                          </div> 
                             <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="<?php echo $tip_status_global; ?>"><?php echo $module_status; ?></span></label>
                            <div class="col-sm-10">
                              <select name="xshippingpro_status" id="input-status" class="form-control">
                                <?php if ($xshippingpro_status) { ?>
                                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                  <option value="0"><?php echo $text_disabled; ?></option>
                                  <?php } else { ?>
                                  <option value="1"><?php echo $text_enabled; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                              </select>
                             </div>
                          </div>
                            <div class="form-group">
                            <label class="col-sm-2 control-label" for="xshippingpro_debug"><span data-toggle="tooltip" title="<?php echo $tip_debug; ?>"><?php echo $text_debug; ?></span></label>
                            <div class="col-sm-10">
                              <select name="xshippingpro_debug" id="xshippingpro_debug" class="form-control">
                               <?php if ($xshippingpro_debug) { ?>
                                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                  <option value="0"><?php echo $text_disabled; ?></option>
                                  <?php } else { ?>
                                  <option value="1"><?php echo $text_enabled; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                  <?php } ?>
                              </select>
                             </div>
                          </div>
                         </div>
                         <div class="tab-pane" id="global-export">
                            <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-import"><span data-toggle="tooltip" title="<?php echo $tip_import; ?>"><?php echo $text_import; ?></span></label>
                              <div class="col-sm-8">
                                 <input type="file" class="form-control" id="input-import" accept="text/txt" name="file_import" />
                              </div>
                              <div class="col-sm-2">
                                <button type="submit" form="form-auspost" data-toggle="tooltip" name="action" title="<?php echo $text_import; ?>" value="import" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-2 control-label" for="input-export"><span data-toggle="tooltip" title="<?php echo $tip_export; ?>"><?php echo $text_export; ?></span></label>
                              <div class="col-sm-10">
                                 <a href="<?php echo $export;?>" target="_blank" class="btn btn-primary"><?php echo $text_export; ?></a>
                              </div>
                           </div>
                         </div> 
                         <div class="tab-pane" id="global-group">
                           <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-group"><span data-toggle="tooltip" title="<?php echo $tip_grouping; ?>"><?php echo $text_group_shipping_mode; ?></span></label>
                            <div class="col-sm-10">
                              <select name="xshippingpro_group" id="input-group" class="form-control xshippingpro_group">
                                <?php
                                   foreach($group_options as $type=>$name){
                                   if($type =='and') continue;
                                    $selected=(isset($xshippingpro_group) && $xshippingpro_group==$type)?'selected':'';
                                  ?>
                                  <option value="<?php echo $type?>" <?php echo $selected?>><?php echo $name?></option>
                                  <?php } ?>
                              </select>
                             </div>
                          </div>
                          <div <?php if($xshippingpro_group!='lowest' && $xshippingpro_group!='highest') echo 'style="display:none;"'; ?> id="group-limit" class="form-group">
                            <label class="col-sm-2 control-label" for="input-group-limit"><span data-toggle="tooltip" title="<?php echo $tip_group_limit; ?>"><?php echo $text_group_limit; ?></span></label>
                            <div class="col-sm-10">
                              <select name="xshippingpro_group_limit" id="input-group-limit" class="form-control">
                                <?php
                                   for($gi=1; $gi<=5; $gi++){
                                    $selected=(isset($xshippingpro_group_limit) && $xshippingpro_group_limit==$gi)?'selected':'';
                                  ?>
                                  <option value="<?php echo $gi?>" <?php echo $selected?>><?php echo $gi?></option>
                                  <?php } ?>
                              </select>
                             </div>
                          </div>
                         <div class="form-group">
              				<label class="col-sm-2 control-label" for="input-method-group"><span data-toggle="tooltip" title="<?php echo $tip_method_group; ?>"> <?php echo $text_method_group;?> </span></label>
              			    <div class="col-sm-10">	
             				 <div class="table-responsive">
                 				<table class="table table-striped table-bordered table-hover">
                    				<thead>
                    					<tr>
                     				 		<td class="text-left">
                       			 				<?php echo $text_group_name;?>
                      						</td>
                      		 				<td class="text-left">
                        						<?php echo $text_group_type;?>
                     		 				</td>
                     		 				<td class="text-left">
                         						<?php echo $text_group_limit;?>
                     		 				</td>
                     		 				<td class="text-left">
                         						<span data-toggle="tooltip" title="<?php echo $tip_group_name; ?>"><?php echo $entry_group_name;?></span>
                     		 				</td>
                     		 		   </tr>		
                   		 			</thead>
                   				 <tbody>
                   		 			<?php
                      
                     		 			for($sg=1; $sg<=$xshippingpro_sub_groups_count;$sg++) {
                      						$current_method_mode = 'lowest';
                    				?>
                    				 <tr>
                      					<td class="text-left">Group<?php echo $sg;?></td>
                      					<td class="text-left">
                       		 				<select class="xshippingpro_sub_group<?php echo $sg;?>" name="xshippingpro_sub_group[<?php echo $sg;?>]">
                  								<?php
                  				 					foreach($group_options as $type=>$name) {
                  					 					if($type =='no_group') continue;
                    			 	 					$selected=(isset($xshippingpro_sub_group[$sg]) && $xshippingpro_sub_group[$sg]==$type)?'selected':'';
                    			  						$current_method_mode = (isset($xshippingpro_sub_group[$sg]) && $xshippingpro_sub_group[$sg]==$type)? $type: $current_method_mode; 
                 								?>
                 				 					<option value="<?php echo $type?>" <?php echo $selected?>><?php echo $name?></option>
                 				 				<?php } ?>
               				 				</select>
                     	 				</td>
                     	 				<td class="text-left"> 
                         						<select <?php if($current_method_mode!='lowest' && $current_method_mode!='highest') echo 'style="display:none;"'; ?> class="xshippingpro_sub_group_limit<?php echo $sg;?>" name="xshippingpro_sub_group_limit[<?php echo $sg;?>]">
                									<?php
                   										for($gi=1; $gi<=5; $gi++){
                    										$selected=(isset($xshippingpro_sub_group_limit[$sg]) && $xshippingpro_sub_group_limit[$sg]==$gi)?'selected':'';
                  										?>
                  										<option value="<?php echo $gi?>" <?php echo $selected?>><?php echo $gi?></option>
                 				 					<?php } ?>
               				 					</select>
                      					</td>
                      					<td class="text-left"> 
                      					     <input type="text" name="xshippingpro_sub_group_name[<?php echo $sg;?>]" value="<?php echo isset($xshippingpro_sub_group_name[$sg])?$xshippingpro_sub_group_name[$sg]:''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                      					</td>
                    				</tr> 
                     			  <?php } ?> 
                    		    </tbody>
                  		       </table>
                  		       </div>
              			     </div>
            		     </div>
                         </div>   
                        </div>   
                       </div> <!--end of tab general-->
                       <?php  echo $form_data;?>
                   </div>
                 </div>
               </div>      
            </form>
      </div>
    </div>
  </div>

<style type="text/css">
.xshipping-checkbox label{ margin-right: 15px;}
.xshipping-checkbox label input {
    margin-right: 5px;
	margin-bottom:4px;
}
.well-sm{ display:none;}
.well-days, .well-desc, .product-category, .product-product{ display:block;}
div.category, div.product, div.postal-option, div.range-option, div.coupon-option, div.manufacturer-option, div.dimensional-option{ display:none;}
label.any-class {
    margin-top: 4px;
}
div.range-option textarea{height: 45px;}
.tbl-wrapper{ width:99%;}
.import-btn-wrapper{width:99%; height:auto; overflow:hidden; margin-bottom:10px;}
.import-btn-wrapper a.btn{ float:right;margin-right: 5px;}
input.import-csv[type="file"]{ display:none;}
div.shipping {
    position: relative;
}
.action-btn {
    position: absolute;
    right: 0;
    top: -5px;
}
.action-btn button {
    margin-left: 5px;
}
button.add-new {
    margin-top: 10px;
}

div.tooltip div.tooltip-inner{ font-weight:normal !important; text-align:left !important;}
div.tooltip div.tooltip-inner b{ display:block !important;}
.global-waiting{display:block;position:fixed; width:124px; height:34px; text-align:center;font-size:16px;font-weight:bold; color:#ffffff;background-color:#D96E7C; border-radius:5px;padding-top:5px;}
.fa-minus-circle{ cursor:pointer;}
/* End of new*/

</style>
<script type="text/javascript"><!--
var current_tab=1;    
var range ='<tr>'; 
    range += '    <td class="text-left"><input size="15" type="text" name="xshippingpro[rate_start][__INDEX__][]" class="form-control" value="__VALUE_START__" /></td>';
    range += '    <td class="text-left"><input size="15" type="text" name="xshippingpro[rate_end][__INDEX__][]" class="form-control" value="__VALUE_END__" /></td>';
    range += '    <td class="text-left"><input size="15" type="text" name="xshippingpro[rate_total][__INDEX__][]" class="form-control" value="__VALUE_COST__" /></td>';
    range += '    <td class="text-left"><input size="6" type="text" name="xshippingpro[rate_block][__INDEX__][]" class="form-control" value="__VALUE_PG__" /></td>';
	range += '    <td class="text-left"><select name="xshippingpro[rate_partial][__INDEX__][]"><option __VALUE_PA1__ value="1"><?php echo $text_yes;?></option><option __VALUE_PA2__ value="0"><?php echo $text_no;?></option></select></td>';
    range += '    <td class="text-right"><a class="btn btn-danger remove-row"><?php echo $text_remove;?></a></td>';
    range += '  </tr>';
       
    
var tmp='<div id="__ID__" class="tab-pane shipping">'
          +'<div class="action-btn">'
		     +'<button class="btn btn-warning btn-copy" data-toggle="tooltip" type="button" data-original-title="<?php echo $text_method_copy;?>"><i class="fa fa-copy"></i></button>'
			 +'<button class="btn btn-danger btn-delete" data-toggle="tooltip" type="button" data-original-title="<?php echo $text_method_remove;?>"><i class="fa fa-trash-o"></i></button>'
		   +'</div>'
          +'<ul class="nav nav-tabs" id="language__INDEX__">'
            <?php $inc=0; foreach ($languages as $language) { $active_cls=($inc==0)?'class="active"':''; $inc++; ?>
             +'<li <?php echo $active_cls; ?> ><a href="#language<?php echo $language['language_id']; ?>__INDEX__" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>'
            <?php } ?>
          +' </ul>'
		  +'<div class="tab-content">'
         <?php $inc=0;foreach ($languages as $language) { $active_cls=($inc==0)?' active':''; $lang_cls=($inc==0)?'':'-lang'; $inc++;  ?>
          +'<div class="tab-pane<?php echo $active_cls;?>" id="language<?php echo $language['language_id']; ?>__INDEX__">'
		    +'<div class="form-group required">'
				+'<label class="col-sm-2 control-label" for="lang-name-__INDEX__<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>'
				+'<div class="col-sm-10">'
				 +'<input type="text" name="xshippingpro[name][__INDEX__][<?php echo $language['language_id']; ?>]" value="" placeholder="<?php echo $entry_name; ?>" id="lang-name-__INDEX__<?php echo $language['language_id']; ?>" class="form-control method-name<?php echo $lang_cls?>" />'
				 +'</div>'
			  +'</div>'
			  +'<div class="form-group">'
				 +'<label class="col-sm-2 control-label" for="lang-desc-__INDEX__<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo addslashes($tip_desc)?>"><?php echo $entry_desc; ?> </span></label>'
				 +'<div class="col-sm-10">'
				  +'<input type="text" name="xshippingpro[desc][__INDEX__][<?php echo $language['language_id']; ?>]" value="" placeholder="<?php echo $entry_desc; ?>" id="lang-desc-__INDEX__<?php echo $language['language_id']; ?>" class="form-control" />'
				  +'</div>'
			  +'</div>'
	   +'</div>'
	  <?php } ?>
	  +'</div>'
      +'<ul class="nav nav-tabs" id="method-tab-__INDEX__">'
             +'<li class="active"><a href="#common___INDEX__" data-toggle="tab"><?php echo $text_general?></a></li>'
             +'<li><a href="#criteria___INDEX__" data-toggle="tab"><?php echo $text_criteria_setting?></a></li>'
             +'<li><a href="#catprod___INDEX__" data-toggle="tab"><?php echo $text_category_product?></a></li>'
             +'<li><a href="#price___INDEX__" data-toggle="tab"><?php echo $text_price_setting?></a></li>'
             +'<li><a href="#other___INDEX__" data-toggle="tab"><?php echo $text_others;?></a></li>'
           +'</ul>' 
		   +'<div class="tab-content">'
           +'<div class="tab-pane active" id="common___INDEX__">'
		        +'<div class="form-group">'
                   +'<label class="col-sm-2 control-label" for="input-weight__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_weight_include)?>"><?php echo $entry_weight_include; ?></span></label>'
                   +'<div class="col-sm-10"><input type="checkbox" name="xshippingpro[inc_weight][__INDEX__]" value="1" id="input-weight__INDEX__" /></div>'
                +'</div>'
                +'<div class="form-group">'
                  +'<label class="col-sm-2 control-label" for="input-tax-class__INDEX__"><?php echo $entry_tax; ?></label>'
                  +'<div class="col-sm-10"><select id="input-tax-class__INDEX__" name="xshippingpro[tax_class_id][__INDEX__]" class="form-control" >'
                  +'<option value="0"><?php echo $text_none; ?></option>'
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  +'<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo addslashes($tax_class['title']); ?></option>'
                  <?php } ?>
                  +'</select></div>'
                +'</div>'
                +'<div class="form-group">'
                  +'<label class="col-sm-2 control-label" for="input-logo__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_text_logo)?>"><?php echo $text_logo; ?> </span></label>'
                  +'<div class="col-sm-10"><input type="text" name="xshippingpro[logo][__INDEX__]" value="" class="form-control" id="input-logo__INDEX__" /></div>'
                +'</div>'
                +'<div class="form-group">'
                  +'<label class="col-sm-2 control-label" for="input-sortorder__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_sorting_own)?>"><?php echo $entry_sort_order; ?> </span></label>'
                  +'<div class="col-sm-10"><input type="text" name="xshippingpro[sort_order][__INDEX__]" value="" class="form-control" id="input-sortorder__INDEX__" /></div>'
                +'</div>'
                +'<div class="form-group">'
                  +'<label class="col-sm-2 control-label" for="input-status__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_status_own)?>"><?php echo $entry_status; ?></span></label>'
                  +'<div class="col-sm-10"><select class="form-control" id="input-status__INDEX__" name="xshippingpro[status][__INDEX__]">'
                  +'<option value="1" selected="selected"><?php echo $text_enabled; ?></option>'
                  +'<option value="0"><?php echo $text_disabled; ?></option>'
                  +'</select></div>'
                +'</div>'
                +'<div class="form-group">'
                  +'<label class="col-sm-2 control-label" for="input-group__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($entry_group_tip)?>"><?php echo $entry_group; ?></span></label>'
                  +'<div class="col-sm-10"><select class="form-control" id="input-group__INDEX__" name="xshippingpro[group][__INDEX__]">'
                  +'<option value="0"><?php echo $text_group_none; ?></option>'
                  <?php for($sg=1; $sg<=$xshippingpro_sub_groups_count;$sg++) { ?>
                  +'<option value="<?php echo $sg; ?>">Group<?php echo $sg; ?></option>'
                  <?php } ?>
                  +'</select></div>'
                +'</div>'
                +'<div class="form-group">'
                  +'<label class="col-sm-2 control-label" for="input-textcolor__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_text_color)?>"><?php echo $entry_text_color; ?> </span></label>'
                  +'<div class="col-sm-10"><input type="color" name="xshippingpro[text_color][__INDEX__]" value="#000000" class="form-control" id="input-textcolor__INDEX__" /></div>'
                +'</div>'
                +'<div class="form-group">'
                  +'<label class="col-sm-2 control-label" for="input-backgroundcolor__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_background_color)?>"><?php echo $entry_background_color; ?> </span></label>'
                  +'<div class="col-sm-10"><input type="color" name="xshippingpro[background_color][__INDEX__]" value="#ffffff" class="form-control" id="input-backgroundcolor__INDEX__" /></div>'
                +'</div>'
            +'</div>'
            +'<div class="tab-pane" id="criteria___INDEX__">'
               +'<div class="form-group">'
                +'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_store)?>"><?php echo $entry_store; ?></span></label>' 
                 +'<div class="col-sm-10">'
		            +'<label class="any-class"><input checked type="checkbox" name="xshippingpro[store_all][__INDEX__]" class="choose-any" value="1" />&nbsp;<?php echo $text_any; ?></label>'
		            +'<div class="well well-sm" style="height: 70px; overflow: auto;">'
		             +'<div class="checkbox xshipping-checkbox">'
                     <?php 
                    foreach ($stores as $store) {
                     ?>
		              +'<label>'
                       +'<input type="checkbox" name="xshippingpro[store][__INDEX__][]" value="<?php echo $store['store_id']; ?>" /><?php echo addslashes($store['name']); ?>'
		             +'</label>'
                 <?php } ?>
                   +'</div>'
				   +'</div>'
	            +'</div>'
               +'</div>'
			   
			   +'<div class="form-group">'
                +'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_geo)?>"><?php echo $entry_geo_zone; ?></span></label>' 
                 +'<div class="col-sm-10">'
		            +'<label class="any-class"><input checked type="checkbox" name="xshippingpro[geo_zone_all][__INDEX__]" class="choose-any" value="1" />&nbsp;<?php echo $text_any; ?></label>'
		            +'<div class="well well-sm" style="height: 100px; overflow: auto;">'
		             +'<div class="checkbox xshipping-checkbox">'
                     <?php 
                    foreach ($geo_zones as $geo_zone) {
                     ?>
		              +'<label>'
                       +'<input type="checkbox" name="xshippingpro[geo_zone_id][__INDEX__][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" /><?php echo addslashes($geo_zone['name']); ?>'
		             +'</label>'
                 <?php } ?>
                   +'</div>'
				   +'</div>'
	            +'</div>'
               +'</div>'
               
               +'<div class="form-group">'
                +'<label class="col-sm-2 control-label"><?php echo $text_country; ?></label>' 
                 +'<div class="col-sm-10">'
		            +'<label class="any-class"><input checked type="checkbox" name="xshippingpro[country_all][__INDEX__]" class="choose-any" value="1" />&nbsp;<?php echo $text_any; ?></label>'
		            +'<div class="well well-sm" style="height: 115px; overflow: auto;padding: 0;border-radius: 0;box-shadow: none;background: none;border: none;">'
		            +'<select class="form-control" multiple="true" size="5" name="xshippingpro[country][__INDEX__][]">'
                     <?php 
                    foreach ($countries as $country) {
                     ?>
		              +'<option value="<?php echo $country['country_id'];?>"><?php echo addslashes($country['name']);?></option>'
                    <?php } ?>
				   +'</select></div>'
	            +'</div>'
               +'</div>'
			   
			    +'<div class="form-group">'
                +'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_customer_group)?>"><?php echo $entry_customer_group; ?></span></label>' 
                 +'<div class="col-sm-10">'
		            +'<label class="any-class"><input checked type="checkbox" name="xshippingpro[customer_group_all][__INDEX__]" class="choose-any" value="1" />&nbsp;<?php echo $text_any; ?></label>'
		            +'<div class="well well-sm" style="height: 70px; overflow: auto;">'
		             +'<div class="checkbox xshipping-checkbox">'
                     <?php 
                     foreach ($customer_groups as $customer_group) {
                     ?>
		              +'<label>'
                       +'<input type="checkbox" name="xshippingpro[customer_group][__INDEX__][]" value="<?php echo $customer_group['customer_group_id']; ?>" /><?php echo addslashes($customer_group['name']); ?>'
		             +'</label>'
                 <?php } ?>
                   +'</div>'
				   +'</div>'
	            +'</div>'
               +'</div>'
			   
			   +'<div class="form-group">'
                +'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_manufacturer)?>"><?php echo $entry_manufacturer; ?></span></label>' 
                 +'<div class="col-sm-10">'
		            +'<label class="any-class"><input checked type="checkbox" name="xshippingpro[manufacturer_all][__INDEX__]" class="choose-any-with" rel="manufacturer-option" value="1" />&nbsp;<?php echo $text_any; ?></label>'
		            +'<div class="well well-sm" style="height: 100px; overflow: auto;">'
		             +'<div class="checkbox xshipping-checkbox">'
                     <?php 
                    foreach ($manufacturers as $manufacturer) {
                     ?>
		              +'<label>'
                       +'<input type="checkbox" name="xshippingpro[manufacturer][__INDEX__][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" /><?php echo addslashes($manufacturer['name']); ?>'
		             +'</label>'
                 <?php } ?>
                   +'</div>'
				   +'</div>'
	            +'</div>'
               +'</div>'
                +'<div class="form-group manufacturer-option">'
                +'<label class="col-sm-2 control-label" for="input-make-rule__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_manufacturer_rule)?>"><?php echo $text_manufacturer_rule?></span></label>'
                +'<div class="col-sm-10"><select class="form-control" id="input-make-rule__INDEX__" name="xshippingpro[manufacturer_rule][__INDEX__]">'
                   +'<option value="2"><?php echo $text_manufacturer_all; ?></option>'
		           +'<option value="3"><?php echo $text_manufacturer_least_with_other; ?></option>'
		           +'<option value="6"><?php echo $text_manufacturer_least; ?></option>'
		           +'<option value="4"><?php echo $text_manufacturer_exact; ?></option>'
		           +'<option value="5"><?php echo $text_manufacturer_except; ?></option>'
				   +'<option value="7"><?php echo $text_manufacturer_except_other; ?></option>'
                  +'</select></div>'
               +'</div>'
			   
			   +'<div class="form-group">'
                +'<label class="col-sm-2 control-label" for="input-postal__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_zip)?>"><?php echo $text_zip_postal; ?></span></label>' 
                 +'<div class="col-sm-10">'
		            +'<label class="any-class"><input checked type="checkbox" name="xshippingpro[postal_all][__INDEX__]" class="choose-any-with" rel="postal-option" value="1" id="input-postal__INDEX__" /><?php echo $text_any; ?></label>'
	            +'</div>'
               +'</div>'
               +'<div class="form-group postal-option">'
                +'<label class="col-sm-2 control-label" for="input-zip__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_postal_code)?>"><?php echo $text_enter_zip?></span></label>'
                +'<div class="col-sm-10"><textarea class="form-control" id="input-zip__INDEX__" name="xshippingpro[postal][__INDEX__]" rows="8" cols="70" /></textarea></div>'
               +'</div>'
               +'<div class="form-group postal-option">'
                +'<label class="col-sm-2 control-label" for="input-zip-rule__INDEX__"><?php echo $text_zip_rule?></label>'
                +'<div class="col-sm-10"><select class="form-control" id="input-zip-rule__INDEX__" name="xshippingpro[postal_rule][__INDEX__]">'
                    +'<option value="inclusive"><?php echo $text_zip_rule_inclusive?></option>'
                    +'<option value="exclusive"><?php echo $text_zip_rule_exclusive?></option>'
                  +'</select></div>'
               +'</div>'  
			   
			    +'<div class="form-group">'
                +'<label class="col-sm-2 control-label" for="input-coupon__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_coupon)?>"><?php echo $text_coupon; ?></span></label>' 
                 +'<div class="col-sm-10">'
		            +'<label class="any-class"><input checked type="checkbox" name="xshippingpro[coupon_all][__INDEX__]" class="choose-any-with" rel="coupon-option" value="1" id="input-coupon__INDEX__" /><?php echo $text_any; ?></label>'
	            +'</div>'
               +'</div>'
               +'<div class="form-group coupon-option">'
                +'<label class="col-sm-2 control-label" for="input-coupon-here__INDEX__"><?php echo $text_enter_coupon?></label>'
                +'<div class="col-sm-10"><textarea class="form-control" id="input-coupon-here__INDEX__" name="xshippingpro[coupon][__INDEX__]" rows="8" cols="70" /></textarea></div>'
               +'</div>'
               +'<div class="form-group coupon-option">'
                +'<label class="col-sm-2 control-label" for="input-coupon-rule__INDEX__"><?php echo $text_coupon_rule?></label>'
                +'<div class="col-sm-10"><select class="form-control" id="input-coupon-rule__INDEX__" name="xshippingpro[coupon_rule][__INDEX__]">'
                    +'<option value="inclusive"><?php echo $text_coupon_rule_inclusive?></option>'
                    +'<option value="exclusive"><?php echo $text_coupon_rule_exclusive?></option>'
                  +'</select></div>'
               +'</div>'
         +'</div>' 
         +'<div class="tab-pane" id="catprod___INDEX__">'
	        +'<div class="form-group">'
              +'<label class="col-sm-2 control-label" for="input-cat-rule__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_category)?>"><?php echo $text_category; ?></span></label>'
              +'<div class="col-sm-10"><select id="input-cat-rule__INDEX__" class="form-control selection" rel="category" name="xshippingpro[category][__INDEX__]">'
                  +'<option value="1"><?php echo $text_category_any; ?></option>'
                  +'<option value="2"><?php echo $text_category_all; ?></option>'
		          +'<option value="3"><?php echo $text_category_least_with_other; ?></option>'
		          +'<option value="6"><?php echo $text_category_least; ?></option>'
		          +'<option value="4"><?php echo $text_category_exact; ?></option>'
		          +'<option value="5"><?php echo $text_category_except; ?></option>'
				   +'<option value="7"><?php echo $text_category_except_other; ?></option>'
                +'</select></div>'
            +'</div>'
			 +'<div class="form-group category">'
              +'<label class="col-sm-2 control-label" for="input-mul-cat-rule__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_multi_category)?>"><?php echo $text_multi_category; ?></span></label>'
              +'<div class="col-sm-10"><select id="input-mul-cat-rule__INDEX__" class="form-control" name="xshippingpro[multi_category][__INDEX__]">'
                  +'<option value="all"><?php echo $entry_all; ?></option>'
                  +'<option value="any"><?php echo $entry_any; ?></option>'
                +'</select></div>'
            +'</div>'
	        +'<div class="form-group category">'
              +'<label class="col-sm-2 control-label" for="input-category__INDEX__"><?php echo $entry_category; ?></label>'
              +'<div class="col-sm-10"><input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category__INDEX__" class="form-control" />'
			     +'<div class="well well-sm product-category" style="height: 150px; overflow: auto;">'
				 +'</div>'
			   +'</div>'
            +'</div>'
            +'<div class="form-group">'
              +'<label class="col-sm-2 control-label" for="input-product_rule__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_product)?>"><?php echo $text_product; ?></span></label>'
              +'<div class="col-sm-10"><select id="input-product_rule__INDEX__" class="form-control selection" rel="product" name="xshippingpro[product][__INDEX__]">'
                  +'<option value="1"><?php echo $text_product_any; ?></option>'
                  +'<option value="2"><?php echo $text_product_all; ?></option>'
	              +'<option value="3"><?php echo $text_product_least_with_other; ?></option>'
	              +'<option value="6"><?php echo $text_product_least; ?></option>'
	              +'<option value="4"><?php echo $text_product_exact; ?></option>'
	              +'<option value="5"><?php echo $text_product_except; ?></option>'
				   +'<option value="7"><?php echo $text_product_except_other; ?></option>'
                +'</select></div>'
             +'</div>'
			  +'<div class="form-group product">'
              +'<label class="col-sm-2 control-label" for="input-product__INDEX__"><?php echo $entry_product; ?></label>'
              +'<div class="col-sm-10"><input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product__INDEX__" class="form-control" />'
			     +'<div class="well well-sm product-product" style="height: 150px; overflow: auto;">'
				 +'</div>'
			   +'</div>'
            +'</div>'
          +'</div>'
         +'<div class="tab-pane" id="price___INDEX__">'
            +'<div class="form-group">'
              +'<label class="col-sm-2 control-label" for="input-rate__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_rate_type)?>"><?php echo $text_rate_type?></span></label>'
              +'<div class="col-sm-10"><select id="input-rate__INDEX__" class="rate-selection form-control" name="xshippingpro[rate_type][__INDEX__]">'
                  +'<option value="flat"><?php echo $text_rate_flat?></option>'
                  +'<option value="quantity"><?php echo $text_rate_quantity?></option>'
                  +'<option value="weight"><?php echo $text_rate_weight?></option>'
				   +'<option value="dimensional"><?php echo $text_dimensional_weight?></option>'
                  +'<option value="volume"><?php echo $text_rate_volume?></option>'
		          +'<option value="total"><?php echo $text_rate_total?></option>'
		          +'<option value="total_coupon"><?php echo $text_rate_total_coupon?></option>'
                  +'<option value="sub"><?php echo $text_rate_sub_total?></option>'
                  +'<option value="grand"><?php echo $text_grand_total?></option>'
                  +'<option value="total_method"><?php echo $text_rate_total_method?></option>'
                  +'<option value="sub_method"><?php echo $text_rate_sub_total_method?></option>'
                  +'<option value="quantity_method"><?php echo $text_rate_quantity_method?></option>'
                  +'<option value="weight_method"><?php echo $text_rate_weight_method?></option>'
				   +'<option value="dimensional_method"><?php echo $text_dimensional_weight_method?></option>'
                  +'<option value="volume_method"><?php echo $text_rate_volume_method?></option>'
                +'</select></div>'
            +'</div>'
			+'<div class="form-group dimensional-option">'
             +'<label class="col-sm-3 control-label" for="input-dimension_factor__INDEX__"><?php echo $text_dimensional_factor; ?></label>'
             +'<div class="col-sm-9"><input id="input-dimension_factor__INDEX__" type="text" name="xshippingpro[dimensional_factor][__INDEX__]" value="" class="form-control" /></div>'
           +'</div>'
			+'<div class="form-group dimensional-option">'
                +'<label class="col-sm-4 control-label" for="input-dimension_overrule__INDEX__"><?php echo $text_dimensional_overrule; ?></label>'
                +'<div class="col-sm-8"><input id="input-dimension_overrule__INDEX__" type="checkbox" name="xshippingpro[dimensional_overfule][__INDEX__]" value="1" /></div>'
            +'</div>'
           +'<div class="form-group single-option">'
             +'<label class="col-sm-2 control-label" for="input-cost__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_cost)?>"><?php echo $entry_cost; ?></span></label>'
             +'<div class="col-sm-10"><input id="input-cost__INDEX__" class="form-control" type="text" name="xshippingpro[cost][__INDEX__]" value="" /></div>'
           +'</div>'
           +'<div class="form-group range-option">'
             +'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_import)?>"><?php echo $text_unit_range?></span></label>'
             +'<div class="col-sm-10">'
			  +'<div class="tbl-wrapper">'
			    +'<div class="import-btn-wrapper">'
                +'<a class="btn btn-danger delete-all rate-btn"><?php echo $text_delete_all?></a>&nbsp;<a  class="btn btn-primary import-btn rate-btn"><?php echo $text_csv_import?></a>'
			    +'</div>'
			    +'<div class="table-responsive">'
               +'<table class="table table-striped table-bordered table-hover">'
                  +'<thead>'
                   +'<tr>'
                    +'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_unit_start)?>"><?php echo $text_start?></span></label></td>'
					   +'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_unit_end)?>"><?php echo $text_end?></span></label></td>'
					   +'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_unit_price)?>"><?php echo $text_cost?></span></label></td>'
					   +'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_unit_ppu)?>"><?php echo $text_qnty_block?></span></label></td>'
					   +'<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_partial)?>"><?php echo $text_partial?></span></label></td>'
					   +'<td class="left"></td>'
                   +'</tr>'
                  +'</thead>'
                  +'<tbody>'
				    +'<tr class="no-row"><td colspan="6"><?php echo $no_unit_row;?></td></tr>'
                  +'</tbody>'
                  +'<tfoot>'
                    +'<tr>'
                     +'<td colspan="5">&nbsp;</td>'
                     +'<td class="right">&nbsp;<a class="btn btn-primary add-row"><i class="fa fa-plus-circle"></i><?php echo $text_add_new?></span></label>'
                    +'</tr>'
                   +'</tfoot>'     
                 +'</table>'
                +'</div>'
				+'</div>'
				+'</div>'
            +'</div>'
			+'<div class="form-group range-option">'
             +'<label class="col-sm-2 control-label" for="input-additional__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_additional)?>"><?php echo $text_additional; ?></span></label>'
             +'<div class="col-sm-10"><input id="input-additional__INDEX__" class="form-control" type="text" name="xshippingpro[additional][__INDEX__]" value="" /></div>'
           +'</div>'
            +'<div class="form-group range-option">'
              +'<label class="col-sm-2 control-label" for="input-rate-final__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_single_commulative)?>"><?php echo $text_final_cost?></span></label>'
              +'<div class="col-sm-10"><select id="input-rate-final__INDEX__" class="form-control" name="xshippingpro[rate_final][__INDEX__]">'
                  +'<option value="single"><?php echo $text_final_single?></option>'
                  +'<option value="cumulative"><?php echo $text_final_cumulative?></option>'
                +'</select></div>'
            +'</div>'
            +'<div class="form-group">'
              +'<label class="col-sm-2 control-label" for="input-rate-percent__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_percentage)?>"><?php echo $text_percentage_related?></span></label>'
              +'<div class="col-sm-10"><select class="form-control" id="input-rate-percent__INDEX__" name="xshippingpro[rate_percent][__INDEX__]">'
                  +'<option value="sub"><?php echo $text_percent_sub_total?></option>'
                  +'<option value="total"><?php echo $text_percent_total?></option>'
                   +'<option value="shipping"><?php echo $text_percent_shipping?></option>'
                  +'<option value="sub_shipping"><?php echo $text_percent_sub_total_shipping?></option>'
                  +'<option value="total_shipping"><?php echo $text_percent_total_shipping?></option>'
                +'</select></div>'
            +'</div>'
            +'<div class="form-group single-option">'
             +'<label class="col-sm-2 control-label" for="input-mask__INDEX__"><?php echo $text_mask_price; ?></label>'
             +'<div class="col-sm-10"><input id="input-maks__INDEX__" class="form-control" type="text" name="xshippingpro[mask][__INDEX__]" value="" /></div>'
           +'</div>'
            +'<div class="form-group range-option">'
                +'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_price_adjust)?>"><?php echo $text_price_adjustment?></span></label>'
                +'<div class="col-sm-10">'
				    +'<div class="row">'
					   +'<div class="col-sm-4">'
					    +' <input class="form-control" type="text" name="xshippingpro[rate_min][__INDEX__]" placeholder="<?php echo $text_price_min?>" value="" />'
					    +'</div>'
						+'<div class="col-sm-4">'
						   +'<input class="form-control" type="text" name="xshippingpro[rate_max][__INDEX__]" placeholder="<?php echo $text_price_max?>" value="" />'
						 +'</div>'  
						 +'<div class="col-sm-4">'
						   +'<input class="form-control" type="text" name="xshippingpro[rate_add][__INDEX__]" placeholder="<?php echo $text_price_add?>" value="" />'
					    +'</div>'	   
                 +'</div>'
                 +'<div class="row"><div class="col-sm-12"><input type="checkbox" value="1" name="xshippingpro[modifier_ignore][__INDEX__]" /><?php echo $ignore_modifier?></div></div>'
                +'</div>'
            +'</div>'
            +'<div class="form-group range-option">'
             +'<label class="col-sm-2 control-label" for="input-equation__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_equation)?>"><?php echo $text_equation; ?></span></label>'
             +'<div class="col-sm-10"><textarea class="form-control" id="lang-equation__INDEX__" name="xshippingpro[equation][__INDEX__]" rows="8" cols="70" /></textarea><?php echo $text_equation_help;?></div>'
           +'</div>'
		    +'</div>'
           +'<div class="tab-pane" id="other___INDEX__">'
             +'<div class="form-group">'
              +'<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo addslashes($tip_day)?>"><?php echo $text_days_week?></span></label>'
              +'<div class="col-sm-10">'
			      +'<div class="well well-sm well-days" style="height: 60px; overflow: auto;">'
				    +'<div class="checkbox xshipping-checkbox">' 
				      +'<label><input name="xshippingpro[days][__INDEX__][]" checked type="checkbox" value="0" />&nbsp; <?php echo $text_sunday?></label>'
                    +'<label><input name="xshippingpro[days][__INDEX__][]" checked type="checkbox" value="1" />&nbsp; <?php echo $text_monday?></label>'
                    +'<label><input name="xshippingpro[days][__INDEX__][]" checked type="checkbox" value="2" />&nbsp; <?php echo $text_tuesday?></label>'
                    +'<label><input name="xshippingpro[days][__INDEX__][]" checked type="checkbox" value="3" />&nbsp; <?php echo $text_wednesday?></label>'
                    +'<label><input name="xshippingpro[days][__INDEX__][]" checked type="checkbox" value="4" />&nbsp; <?php echo $text_thursday?></label>'
                    +'<label><input name="xshippingpro[days][__INDEX__][]" checked type="checkbox" value="5" />&nbsp; <?php echo $text_friday?></label>'
                    +'<label><input name="xshippingpro[days][__INDEX__][]" checked type="checkbox" value="6" />&nbsp; <?php echo $text_saturday?></label>'
					 +'</div>'
					+'</div>' 
                +'</div>'
             +'</div>'
            +'<div class="form-group">'
                +'<label class="col-sm-2 control-label" for="input-time-start__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_time)?>"><?php echo $text_time_period?></span></label>'
                +'<div class="col-sm-10">'
				    +'<div class="row">'
					    +'<div class="col-sm-4">'
						   +'<select id="input-time-start__INDEX__" class="form-control" name="xshippingpro[time_start][__INDEX__]">'
						  +'<option value=""><?php echo $text_any; ?></option>'
						  <?php for($i = 1; $i <= 24; $i++) { ?>
						  +'<option value="<?php echo $i; ?>"><?php echo date("h:i A", strtotime("$i:00")); ?></option>'
						  <?php } ?>
						  +'</select>'
				       +'</div>'
				       +'<div class="col-sm-4">'
						  +'<select class="form-control" name="xshippingpro[time_end][__INDEX__]">'
						  +'<option value=""><?php echo $text_any; ?></option>'
						  <?php for($i = 1; $i <= 24; $i++) { ?>
						  +'<option value="<?php echo $i; ?>"><?php echo date("h:i A", strtotime("$i:00")); ?></option>'
						  <?php } ?>
						 +'</select>'
				        +'</div>'
				     +'</div>'
                +'</div>'
               +'</div>'
               +'<div class="form-group">'
             +'<label class="col-sm-2 control-label" for="input-total__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_total)?>"><?php echo $entry_order_total?></span></label>'
             +'<div class="col-sm-10">'
                  +'<div class="row-fluid">'
                     +'<div class="col-sm-5">'
                       +'<input size="15" class="form-control" type="text" name="xshippingpro[order_total_start][__INDEX__]" value="" />'
                     +'</div>'
                    +'<div class="col-sm-1"><?php echo $entry_to?></div>'
                    +'<div class="col-sm-5">'
                       +'<input class="form-control" size="15" type="text" name="xshippingpro[order_total_end][__INDEX__]" value="" />'
                    +'</div>'
                  +'</div>'
              +'</div>'
           +'</div>'
		   +'<div class="form-group">'
            +'<label class="col-sm-2 control-label" for="input-total__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_weight)?>"><?php echo $entry_order_weight?></span></label>'
             +'<div class="col-sm-10">'
                  +'<div class="row-fluid">'
                     +'<div class="col-sm-5">'
                       +'<input size="15" class="form-control" type="text" name="xshippingpro[weight_start][__INDEX__]" value="" />'
                     +'</div>'
                    +'<div class="col-sm-1"><?php echo $entry_to?></div>'
                    +'<div class="col-sm-5">'
                       +'<input class="form-control" size="15" type="text" name="xshippingpro[weight_end][__INDEX__]" value="" />'
                    +'</div>'
                  +'</div>'
              +'</div>'
           +'</div>'
		   +'<div class="form-group">'
           +'<label class="col-sm-2 control-label" for="input-total__INDEX__"><span data-toggle="tooltip" title="<?php echo addslashes($tip_quantity)?>"><?php echo $entry_quantity?></span></label>'
             +'<div class="col-sm-10">'
                  +'<div class="row-fluid">'
                     +'<div class="col-sm-5">'
                       +'<input size="15" class="form-control" type="text" name="xshippingpro[quantity_start][__INDEX__]" value="" />'
                     +'</div>'
                    +'<div class="col-sm-1"><?php echo $entry_to?></div>'
                    +'<div class="col-sm-5">'
                       +'<input class="form-control" size="15" type="text" name="xshippingpro[quantity_end][__INDEX__]" value="" />'
                    +'</div>'
                  +'</div>'
              +'</div>'
           +'</div>'
           +'</div>'
		   +'</div>' 
        +'</div>';


 var auto_complete_cat={
				'source': function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
						dataType: 'json',			
						success: function(json) {
							response($.map(json, function(item) {
								return {
									label: item['name'],
									value: item['category_id']
								}
							}));
						}
					});
				},
				'select': function(item) {
					
					var my_method_area=$('#shipping-'+current_tab);
					$('input[name=\'category\']',my_method_area).val('');
					$('.product-category' + item['value'],my_method_area).remove();
					$('.product-category',my_method_area).append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="xshippingpro[product_category]['+current_tab+'][]" value="' + item['value'] + '" /></div>');	
					
				}
	};
	
 var auto_complete_prod={
	 'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	 'select': function(item) {
		 
		var my_method_area=$('#shipping-'+current_tab); 
		$('input[name=\'product\']', my_method_area).val('');
		$('.product-product' + item['value'], my_method_area).remove();
		$('.product-product', my_method_area).append('<div id="product-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="xshippingpro[product_product]['+current_tab+'][]" value="' + item['value'] + '" /></div>');	
	  }	
  };

   
$(document).ready(function () {		
 
	 /* Enable tab*/
	 $('#method-list a:first').tab('show');
	 $('#language-heading a:first').tab('show');
	 
	 $("#method-list").on("click","li",function(){
		 current_tab=$(this).find('a').attr('href').replace('#shipping-','');  
	 });
	 
	 /* Creating New method*/
	 $('.add-new').on('click',function(e) {
		  e.preventDefault();
		  $this=$(this);
		  var no_of_tab=$('#shipping-container').find('div.shipping').length;
		  no_of_tab=parseInt(no_of_tab)+1;
		  //finding qnique id
		  while($('#shipping-'+no_of_tab).length!=0)
		   {
		     no_of_tab++;
		   }
		  var tab_html=tmp;
		  tab_html=tab_html.replace('__ID__','shipping-'+no_of_tab);
		  tab_html=tab_html.replace(/__INDEX__/g, no_of_tab);
		  $('#shipping-container').append(tab_html);
		  
		  $('#method-list').append('<li><a data-toggle="tab" class="tab'+no_of_tab+'" href="#shipping-'+no_of_tab+'">Untitled Method '+no_of_tab+'</a></li>');
		   enableEvents(no_of_tab); 
		   current_tab=no_of_tab;
      });
	 /* End of creating new*/
	 
	 $("#shipping-container").on('click','button.btn-delete', function() { 
		  if(confirm('Are you sure to delete this method?')){
					  $('a.tab'+current_tab).remove();
					  $('#shipping-'+current_tab).remove();
					  $('#method-list a:first').tab('show');
					  
				}
	 });
	 
	 $("#shipping-container").on('click','button.btn-copy', function() { 
		  if(confirm('Are you sure to copy this method?')){
					  copyMethod(current_tab);
				}
	 });
	 
	 
	   $('.xshippingpro_group').change(function(){
		   if($(this).val()=='lowest' || $(this).val()=='highest') {
			  $('#group-limit').show();  
		   }else{
			  $('#group-limit').hide();     
		   }  
		});
		
		$("select[class^='xshippingpro_sub_group']").change(function(){
		   var groupid = $(this).attr('class').replace('xshippingpro_sub_group','');
		   if($(this).val()=='lowest' || $(this).val()=='highest') {
			  $('.xshippingpro_sub_group_limit'+parseInt(groupid)).show();  
		   }else{
			   $('.xshippingpro_sub_group_limit'+parseInt(groupid)).hide();     
		   }  
		
		});
	   
       
	   $("#shipping-container").on('keyup','input.method-name', function() {		
		  var method_name=$(this).val();
		  if(method_name=='')method_name='Untitled Method '+tabId;
		  $('a.tab'+current_tab).html(method_name);
	   }); 
	   
	   
	    $("#shipping-container").on('change','select.selection', function() {
		    
			var relto=$(this).attr('rel');
			 if($(this).val()=='1'){
			    $(this).closest('div.form-group').siblings('div.'+relto).hide();  
			 }else{
			    $(this).closest('div.form-group').siblings('div.'+relto).show();  
			 }
		 }); 
		 
		 $('#shipping-container').delegate('.fa-minus-circle', 'click', function() {
			  $(this).parent().remove();
	     });
		
		  // Category selection
		  $('input[name=\'category\']').autocomplete(auto_complete_cat);
		  $('input[name=\'product\']').autocomplete(auto_complete_prod);
			
		  $("#shipping-container").on('click', '.choose-any', function() {
				 if($(this).prop('checked')){
					 $(this).parent().next('div.well-sm').slideUp();  
				 }else{
					 $(this).parent().next('div.well-sm').slideDown();
				}
			});
			
		  $("#shipping-container").on('click', '.choose-any-with', function() {
                var relto=$(this).attr('rel');
				  
				  if(relto=='manufacturer-option') {
					 if($(this).prop('checked')){
						 $(this).parent().next('div.well-sm').slideUp();  
					 }else{
						 $(this).parent().next('div.well-sm').slideDown();
					 }
				  }
				 	
				 if($(this).prop('checked')){
					 $(this).closest('div.form-group').siblings('div.'+relto).hide();  
				 }else{
					$(this).closest('div.form-group').siblings('div.'+relto).show();  
				}
			});
                
		
        $("#shipping-container").on('change', 'select.rate-selection', function() {
		      
			if($(this).val()=='flat'){
			   $(this).closest('div.form-group').siblings('div.single-option').show();
               $(this).closest('div.form-group').siblings('div.range-option').hide();
			   $(this).closest('div.form-group').siblings('div.dimensional-option').hide();
			   
			}
		   else if($(this).val()=='dimensional' || $(this).val()=='dimensional_method' || $(this).val()=='volume' || $(this).val()=='volume_method'){
			   $(this).closest('div.form-group').siblings('div.single-option').hide();
			   $(this).closest('div.form-group').siblings('div.dimensional-option').show();
			   $(this).closest('div.form-group').siblings('div.range-option').show();
			  
			 }
		   else 
		    {
			  $(this).closest('div.form-group').siblings('div.single-option').hide();
			  $(this).closest('div.form-group').siblings('div.dimensional-option').hide();
             $(this).closest('div.form-group').siblings('div.range-option').show();
            
			 }
		});
                
                
     /* Range Options */
	 $("#shipping-container").on('click','a.add-row',function(){
	          
	          var tpl=range;
			  tpl=tpl.replace(/__INDEX__/g, current_tab);
			  tpl=tpl.replace(/__VALUE_START__/g, '0');
			  tpl=tpl.replace(/__VALUE_END__/g, '0');
			  tpl=tpl.replace(/__VALUE_COST__/g, '0');
			  tpl=tpl.replace(/__VALUE_PG__/g, '0');
			  tpl=tpl.replace(/__VALUE_PA1__/g, '0');
			  tpl=tpl.replace(/__VALUE_PA2__/g, '0');
             
			  $(this).closest('table').find('tbody tr.no-row').remove();     
		      $(this).closest('table').find('tbody').append(tpl);		  
        });
		
		 $("#shipping-container").on('click','a.remove-row',function(){									  
	        $(this).closest('tr').remove();	
			 if($('#shipping-'+current_tab).find('.tbl-wrapper tbody tr').length==0){
			  $('#shipping-'+current_tab).find('.tbl-wrapper tbody').append('<tr class="no-row"><td colspan="6"><?php echo $no_unit_row;?></td></tr>');     	
		    }									  
        });
        
        $("#shipping-container").on('click','a.delete-all',function(){									  
	       $(this).closest('div.tbl-wrapper').find('tbody > tr').remove();	
		   if($('#shipping-'+current_tab).find('.tbl-wrapper tbody tr').length==0) {
			  $('#shipping-'+current_tab).find('.tbl-wrapper tbody').append('<tr class="no-row"><td colspan="6"><?php echo $no_unit_row;?></td></tr>');     	
		    }										  
        });
        
		/* CSV upload*/
		$("#shipping-container").on('click', 'a.import-btn', function() {
		    $('input.import-csv:file').trigger('click');      
		});
		
		$("input.import-csv:file").change(function (){
		    var file = $('input.import-csv:file').get(0).files[0];
			
           var fd = new FormData();
           fd.append('file', file);
		    $.ajax({
					url: 'index.php?route=shipping/xshippingpro/csv_upload&token=<?php echo $token; ?>',
					dataType: 'json',
                  data:fd,
				    processData: false,
                  contentType: false,
                  type:'POST',
				    beforeSend: function(){
				      $('body').append('<div class="global-waiting">Saving...</div>');
			         $('.global-waiting').css({top:'50%',left:'50%',marginTop:'-40px',marginLeft:'-75px'});
					},
					success: function(json) {		
					  
					   if (json['data']) {
							   for(i=0;i<json['data'].length;i++) {
								  var data=json['data'][i];
								  var tpl=range;
								  var pa1=1, pa2=0;
								  if(data.pa=='1') pa1='selected';
								  if(data.pa=='0') pa2='selected';
								  tpl=tpl.replace(/__INDEX__/g, current_tab);
								  tpl=tpl.replace(/__VALUE_START__/g, data.start);
								  tpl=tpl.replace(/__VALUE_END__/g, data.end);
								  tpl=tpl.replace(/__VALUE_COST__/g, data.cost);
								  tpl=tpl.replace(/__VALUE_PG__/g, data.pg);
								  tpl=tpl.replace(/__VALUE_PA1__/g, pa1);
								  tpl=tpl.replace(/__VALUE_PA2__/g, pa2);
								  $('#shipping-'+current_tab).find('.tbl-wrapper tbody').append(tpl);	
							  }
                       }
					    if (json['error']) {
                            alert(json['error']);
                        }
                      $('.global-waiting').remove();	  
					}
			 }); 
		});
          
        /* End of CSV upload*/
         
        /*Quick save*/
        $('#quick_save').click(function(){
			    $('body').append('<div class="global-waiting">Saving...</div>');
			    $('.global-waiting').css({top:'50%',left:'50%',marginTop:'-40px',marginLeft:'-75px'});
				$.ajax({
					url: 'index.php?route=shipping/xshippingpro/quick_save&token=<?php echo $token; ?>',
					dataType: 'json',
                    data:$('#form-auspost').serialize(),
                    type:'POST',
					  success: function(json) {		
					   
					    if (json['error']) {
                            alert(json['error']);
                        }
                       $('.global-waiting').remove();	  
					}
				});
		});           
       
	    
 });
 
 function enableEvents(no_of_tab){ 
		  /* new */
		  var my_method_area=$('#shipping-'+no_of_tab);
         $('#method-list a.tab'+no_of_tab).trigger('click');
		  $('#language'+no_of_tab+' li:first-child').trigger('click');
		  $('#method-tab-'+no_of_tab+' li:first-child').trigger('click');
		  $("[data-toggle='tooltip']",my_method_area).tooltip(); 
		  $('input[name=\'category\']', my_method_area).autocomplete(auto_complete_cat);
		  $('input[name=\'product\']', my_method_area).autocomplete(auto_complete_prod);	  
 }
 
 function copyMethod(tabId) {
   
	      var no_of_tab=$('#shipping-container').find('div.shipping').length;
	      no_of_tab=parseInt(no_of_tab)+1;
	      //finding qnique id
	      while($('#shipping-'+no_of_tab).length!=0)
	      {
		   no_of_tab++;
	      }
          
          var tab_item=$('#shipping-'+tabId).clone()
          var tab_html='<div id="shipping-'+no_of_tab+'" class="tab-pane shipping">'+tab_item.html()+'</div>';
          
		   
 		  tab_html = tab_html.replace(/xshippingpro\[([a-z_]+)\]\[\d+\]/igm, 'xshippingpro[$1]['+no_of_tab+']');
 		  tab_html = tab_html.replace(/_(\d+)/g, '_'+no_of_tab); 
          
		  $('#shipping-container').append(tab_html); 
		  
		  var inputs_text = $('#shipping-'+tabId+' input[type="text"]');
		  var inputs_text_new = $('#shipping-'+no_of_tab+' input[type="text"]');
		  
		  var inputs_checkboxes = $('#shipping-'+tabId+' input[type="checkbox"]');
		  var inputs_checkboxes_new = $('#shipping-'+no_of_tab+' input[type="checkbox"]');
		  
		  var inputs_selects = $('#shipping-'+tabId+' select');
		  var inputs_selects_new = $('#shipping-'+no_of_tab+' select');
		  
		  inputs_text.each(function(index) {
              inputs_text_new.eq(index).val($(this).val());
          });
          
          inputs_selects.each(function(index) {
              inputs_selects_new.eq(index).val($(this).val());
          });
          
          inputs_checkboxes.each(function(index) {
              if($(this).prop('checked')) {
                inputs_checkboxes_new.eq(index).prop('checked','checked');
              } else {
                inputs_checkboxes_new.eq(index).removeAttr('checked');
              }   
          });
	
	
		  $('#method-list').append('<li><a data-toggle="tab" class="tab'+no_of_tab+'" href="#shipping-'+no_of_tab+'">'+$('a.tab'+tabId).html()+'</a></li>');
		  enableEvents(no_of_tab);
		  current_tab=no_of_tab;
 }

 
//--></script>
</div>
<?php echo $footer; ?>