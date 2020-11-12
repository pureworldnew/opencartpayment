<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>
<?php }else{ ?>
<div id="notification2"></div>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <?php if ($category['category_id']=='all') { ?>
      <a class="btn btn-primary" id="setallcategories"  href="javascript:void(0)" ><span><?php echo $button_save_all_cat; ?></span></a> <a data-toggle="tooltip" title="<?php echo $button_close; ?>" onclick="$('#tr_nueva').fadeOut(1000, function() { $(this).remove(); });" class="btn btn-primary"><i class="fa fa-reply"></i></a>
      <?php }else{ ?>
      <a class="btn btn-primary" id="editvalues" data-toggle="tooltip" title="<?php echo $button_save; ?>" href="javascript:void(0)" ><i class="fa fa-check-circle"></i> Save</a> <a data-toggle="tooltip" title="<?php echo $button_close; ?>" onclick="$('#tr_nueva').fadeOut(1000, function() { $(this).remove(); });" class="btn btn-primary"><i class="fa fa-reply"></i></a>
      <?php if($category['parent_id']){ ?>
      <a class="btn btn-primary" id="editallcategories"  href="javascript:void(0)" ><span><?php echo $button_save_all_sub; ?></span></a>
      <?php } ?>
      <?php } ?>
    </div>
  </div>
  <div class="panel-body">
    <form action="<?php echo $action_save_attribute; ?>" method="get" enctype="multipart/form-data" id="categories">
      <input name="dnd" type="hidden" value="VALORES_<?php echo $category['category_id']; ?>" />
      <input name="token" type="hidden" value="<?php echo $token; ?>" />
      <input name="category_id" type="hidden" value="<?php echo $category['category_id']; ?>" />
      <input name="store_id" type="hidden" value="<?php echo $category['store']; ?>" />
      <div style="margin-bottom:15px"></div>
      <ul class="nav nav-tabs" id="tabsValues">
        <li class="active"><a data-toggle="tab" href="#tab-attributes"><?php echo $entry_attributes; ?></a></li>
        <li><a data-toggle="tab" href="#tab-options"><?php echo $entry_options; ?></a></li>
        <li><a data-toggle="tab" href="#tab-ProductInfos"><?php echo $entry_product_info; ?></a></li>
      </ul>
      <?php $i=0; ?>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-attributes">
          <?php if (empty($category['attributes'])) {?>
          <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $no_attributes_c; ?> </div>
          <?php }else{ ?>
		  <div class="table-responsive">
          <table id="tblattributes" class="table table-bordered table-hover" style="font-size:12px !important">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_value; ?></td>
                <td class="text-left"><?php echo $entry_num_products; ?></td>
                <td class="text-left"><?php echo $entry_sort_order; ?></td>
                <td class="text-left"><?php echo $entry_view; ?></td>
                <td class="text-left"><?php echo $entry_unit; ?></td>
                <td class="text-left"><?php echo $entry_separator; ?></td>
                <td class="text-left"><?php echo $text_info; ?></td>
                <td class="text-left"><?php echo $entry_search; ?></td>
                <td class="text-left"><?php echo $entry_open; ?></td>
                <td class="text-left"><?php echo $entry_order; ?></td>
                <td class="text-left"><?php echo $entry_examples; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($category['attributes'] as $value) { ?>
              <tr>
                <td class="text-left">
				<?php $str="attribute_id";      
				if ($value['checked']) { ?>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][<?php echo $str; ?>]" type="checkbox" value="<?php echo $value[$str]; ?>" checked="checked"/>
                  <?php echo $value['name']; ?>
                  <?php }else{ ?>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][<?php echo $str; ?>]" type="checkbox" value="<?php echo $value[$str]; ?>" />
                  <?php echo $value['name']; ?>
                  <?php } ?>
                  <br />
                  <small><?php echo substr(strtoupper($value['what']), 0, -1); ?></small>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][name]" type="hidden" value="<?php echo $value['name']; ?>" />
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][short_name]" type="hidden" value="<?php echo $value['short_name']; ?>" /></td>
                <?php foreach ($languages as $language){?>
                <input type="hidden" id="editor_<?php echo $i; ?>_<?php echo $language['language_id']; ?>" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][text_info][<?php echo $language['language_id']; ?>]" value="<?php echo isset($value['text_info'][$language['language_id']]) ? $value['text_info'][$language['language_id']] : ''; ?>" />
                <?php } ?>
                <td class="text-left">
				<input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][number]" type="text" value="<?php echo $value['number']; ?>" size="3" class="form-control"/></td>
                <td class="text-left">
				<input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][sort_order]" type="text" value="<?php echo $value['sort_order']; ?>" size="3" class="form-control"/></td>
                <td class="text-left">
				<select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][view]">
                    <?php if ($value['view'] == "slider") { ?>
                    <option value="slider" selected="selected"><?php echo $entry_slider; ?></option>
                    <?php } else { ?>
                    <option value="slider"><?php echo $entry_slider; ?></option>
                    <?php } ?>
                    <?php if ($value['view'] == "list") { ?>
                    <option value="list" selected="selected"><?php echo $entry_list; ?></option>
                    <?php } else { ?>
                    <option value="list"><?php echo $entry_list; ?></option>
                    <?php } ?>
                    <?php if ($value['view'] == "sele") { ?>
                    <option value="sele" selected="selected"><?php echo $entry_select; ?></option>
                    <?php } else { ?>
                    <option value="sele"><?php echo $entry_select; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left">
				<input class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][unit]" type="text" value="<?php echo $value['unit']; ?>" size="5"/></td>
                <td class="text-left">
				
				<input class="form-control" onblur="$('#att_<?php echo $value[$str]; ?>').attr('value', $( '#sep_<?php echo $value[$str]; ?>' ).val());" id="sep_<?php echo $value[$str]; ?>" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][separator]" type="text" value="<?php echo $value['separator']; ?>" size="5"/>
                <input id="att_<?php echo $value[$str]; ?>" name="att_<?php echo $value[$str]; ?>" type="hidden" value="<?php echo $value['separator']; ?>" />
                  </td>
				<td class="text-left">
				<div class="input-group">
				<select class="infotext form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][info]">
                    <?php if ($value['info'] == "yes") { ?>
                    <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                    <?php } else { ?>
                    <option value="yes"><?php echo $text_yes; ?></option>
                    <?php } ?>
                    <?php if ($value['info'] == "no") { ?>
                    <option value="no" selected="selected"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="no"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
					  <span class="input-group-btn">
					   <?php if ($value['info'] == "yes") { ?>
							<a rel="<?php echo $i; ?>" class="edit_info_link btn btn-info " type="button"><i class="fa fa-keyboard-o"></i></a>
					   <?php } else { ?>
							<a rel="<?php echo $i; ?>"  class="edit_info_link btn btn-danger disabled" type="button"><i class="fa fa-keyboard-o"></i></a>
					   <?php } ?>
				  </span>
					</div>
				  
				  </td>
					<td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][searchinput]">
                    <?php if ($value['searchinput'] == "yes") { ?>
                    <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                    <?php } else { ?>
                    <option value="yes"><?php echo $text_yes; ?></option>
                    <?php } ?>
                    <?php if ($value['searchinput'] == "no") { ?>
                    <option value="no" selected="selected"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="no"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][initval]">
                    <?php if ($value['initval'] == "opened") { ?>
                    <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                    <?php } else { ?>
                    <option value="opened"><?php echo $text_open; ?></option>
                    <?php } ?>
                    <?php if ($value['initval'] == "closed") { ?>
                    <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                    <?php } else { ?>
                    <option value="closed"><?php echo $text_close; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][orderval]">
                    <?php if ($value['orderval'] == "OHASC") { ?>
                    <option value="OHASC" selected="selected"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OHASC"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OHDESC") { ?>
                    <option value="OHDESC" selected="selected"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OHDESC"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OTASC") { ?>
                    <option value="OTASC" selected="selected"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OTASC"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OTDESC") { ?>
                    <option value="OTDESC" selected="selected"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OTDESC"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OCOASC") { ?>
                    <option value="OCOASC" selected="selected"><?php echo $text_computer; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OCOASC"><?php echo $text_computer; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OCODESC") { ?>
                    <option value="OCODESC" selected="selected"><?php echo $text_computer; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OCODESC"><?php echo $text_computer; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left">
				<?php if($value['what']=="PRODUCT INFOS") { ?>
                 


				 <select  class="form-control" name="select" id="select<?php echo mt_rand(5, 15); ?>" style="width: 150px" >
                    <?php if (count($value['values'])>0){  

				    foreach ($value['values'] as $valores_default){ ?>
                    <option><?php echo trim($valores_default); ?></option>
                    <?php } ?>
                    <?php }else{ ?>
                    <option> <?php echo sprintf($text_none,$value['what']);?></option>
                    <?php } ?>
                  </select>
                  <?php }else{ ?>
					  <?php foreach ($languages as $language) { ?>
					  
					  
					  <div class="input-group">
								
						<span class="input-group-addon"> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />	</span>			  
					  <select  class="form-control" name="select" id="select<?php echo mt_rand(5, 15); ?>" style="width: 150px" >
						<?php  if ($value['values'][$language['language_id']]){ 

						  natsort($value['values'][$language['language_id']]);

						  foreach ($value['values'][$language['language_id']] as $valores_default){ ?>
						<option><?php echo trim($valores_default); ?></option>
						<?php } ?>
						<?php }else{ ?>
						<option> <?php echo sprintf($text_none,$value['what']);?></option>
						<?php } ?>
					  </select>
					 
					  <?php } ?>
					  </div>
                  <?php } ?>
				  
				  
				  
				  </td>
              </tr>
              <tr class="tr_txtinfo_<?php echo $i; ?>" style="display:none;">
                <td>&nbsp;</td>
                <td colspan="9" align="left">
				<?php foreach ($languages as $language){?>
				
				  <div class="input-group">
							<span class="input-group-addon"> <img src="view/image/flags/<?php echo $language['image']; ?>" /></span>
							<textarea class="form-control" id="diveditor_<?php echo $i; ?>_<?php echo $language['language_id']; ?>">
							<?php echo isset($value['text_info'][$language['language_id']]) ? html_entity_decode($value['text_info'][$language['language_id']], ENT_QUOTES, 'UTF-8') : ''; ?> 
							</textarea>
				  </div>
				  
				  <?php } ?>
					  </td>
                <td valign="bottom"><a onclick="$('tr.tr_txtinfo_<?php echo $i; ?>').hide();" class="button btn btn-warning"><span><i class="fa fa-times"></i>&nbsp;<?php echo $button_close; ?></span></a></td>
              </tr>
              <?php $i++; } ?>
            </tbody>
          </table>
          </div>
		  <?php } ?>
        </div>
       
	
	   <div class="tab-pane" id="tab-options">
          <?php if (empty($category['options'])) {?>
          <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $no_options_c; ?> </div>
          <?php }else{ ?>
		  <div class="table-responsive">
          <table id="tbloptions" class="table table-bordered table-hover" style="font-size:12px !important">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_value; ?></td>
                <td class="text-left"><?php echo $entry_num_products; ?></td>
                <td class="text-left"><?php echo $entry_sort_order; ?></td>
                <td class="text-left"><?php echo $entry_view; ?></td>
                <td class="text-left"><?php echo $entry_unit; ?></td>
               
                <td class="text-left"><?php echo $text_info; ?></td>
                <td class="text-left"><?php echo $entry_search; ?></td>
                <td class="text-left"><?php echo $entry_open; ?></td>
                <td class="text-left"><?php echo $entry_order; ?></td>
                <td class="text-left"><?php echo $entry_examples; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($category['options'] as $value) { ?>
              <tr>
                <td class="text-left">
				<?php $str="option_id";      
				if ($value['checked']) { ?>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][<?php echo $str; ?>]" type="checkbox" value="<?php echo $value[$str]; ?>" checked="checked"/>
                  <?php echo $value['name']; ?>
                  <?php }else{ ?>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][<?php echo $str; ?>]" type="checkbox" value="<?php echo $value[$str]; ?>" />
                  <?php echo $value['name']; ?>
                  <?php } ?>
                  <br />
                  <small><?php echo substr(strtoupper($value['what']), 0, -1); ?></small>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][name]" type="hidden" value="<?php echo $value['name']; ?>" />
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][short_name]" type="hidden" value="<?php echo $value['short_name']; ?>" /></td>
                <?php foreach ($languages as $language){?>
                <input type="hidden" id="editor_<?php echo $i; ?>_<?php echo $language['language_id']; ?>" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][text_info][<?php echo $language['language_id']; ?>]" value="<?php echo isset($value['text_info'][$language['language_id']]) ? $value['text_info'][$language['language_id']] : ''; ?>" />
                <?php } ?>
                <td class="text-left">
				<input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][number]" type="text" value="<?php echo $value['number']; ?>" size="3" class="form-control"/></td>
                <td class="text-left">
				<input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][sort_order]" type="text" value="<?php echo $value['sort_order']; ?>" size="3" class="form-control"/></td>
                <td class="text-left">
				<select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][view]">
                    <?php if ($value['view'] == "list") { ?>
                    <option value="list" selected="selected"><?php echo $entry_list; ?></option>
                    <?php } else { ?>
                    <option value="list"><?php echo $entry_list; ?></option>
                    <?php } ?>
                    <?php if ($value['view'] == "sele") { ?>
                    <option value="sele" selected="selected"><?php echo $entry_select; ?></option>
                    <?php } else { ?>
                    <option value="sele"><?php echo $entry_select; ?></option>
                    <?php } ?>
					<?php if ($value['what']=="options"){ ?>
                    <?php if ($value['view'] == "image") { ?>
                    <option value="image" selected="selected"><?php echo $entry_image; ?></option>
                    <?php } else { ?>
                    <option value="image"><?php echo $entry_image; ?></option>
                    <?php } ?>
                    <?php  } ?>
                  </select></td>
                <td class="text-left">
				<input class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][unit]" type="text" value="<?php echo $value['unit']; ?>" size="5"/></td>
               


				  <td class="text-left">
				  <div class="input-group">
					<select class="infotext form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][info]">
                    <?php if ($value['info'] == "yes") { ?>
                    <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                    <?php } else { ?>
                    <option value="yes"><?php echo $text_yes; ?></option>
                    <?php } ?>
                    <?php if ($value['info'] == "no") { ?>
                    <option value="no" selected="selected"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="no"><?php echo $text_no; ?></option>
                    <?php } ?>
					</select>
					<span class="input-group-btn">
					   <?php if ($value['info'] == "yes") { ?>
						<a rel="<?php echo $i; ?>" class="edit_info_link btn btn-info " type="button"><i class="fa fa-keyboard-o"></i></a>
					   <?php } else { ?>
						<a rel="<?php echo $i; ?>"  class="edit_info_link btn btn-danger disabled" type="button"><i class="fa fa-keyboard-o"></i></a>
					   <?php } ?>
					</span>
					</div>
				 
				  </td>
					<td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][searchinput]">
                    <?php if ($value['searchinput'] == "yes") { ?>
                    <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                    <?php } else { ?>
                    <option value="yes"><?php echo $text_yes; ?></option>
                    <?php } ?>
                    <?php if ($value['searchinput'] == "no") { ?>
                    <option value="no" selected="selected"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="no"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][initval]">
                    <?php if ($value['initval'] == "opened") { ?>
                    <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                    <?php } else { ?>
                    <option value="opened"><?php echo $text_open; ?></option>
                    <?php } ?>
                    <?php if ($value['initval'] == "closed") { ?>
                    <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                    <?php } else { ?>
                    <option value="closed"><?php echo $text_close; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][orderval]">
                     <?php if ($value['what']=="options"){ ?>
                    <?php if ($value['orderval'] == "OCASC") { ?>
                    <option value="OCASC" selected="selected"><?php echo $opencart; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OCASC"><?php echo $opencart; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OCDESC") { ?>
                    <option value="OCDESC" selected="selected"><?php echo $opencart; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OCDESC"><?php echo $opencart; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                    <?php } ?>
					
					
					
					
					<?php if ($value['orderval'] == "OHASC") { ?>
                    <option value="OHASC" selected="selected"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OHASC"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OHDESC") { ?>
                    <option value="OHDESC" selected="selected"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OHDESC"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OTASC") { ?>
                    <option value="OTASC" selected="selected"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OTASC"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OTDESC") { ?>
                    <option value="OTDESC" selected="selected"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OTDESC"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OCOASC") { ?>
                    <option value="OCOASC" selected="selected"><?php echo $text_computer; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OCOASC"><?php echo $text_computer; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OCODESC") { ?>
                    <option value="OCODESC" selected="selected"><?php echo $text_computer; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OCODESC"><?php echo $text_computer; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left">
				<?php if($value['what']=="PRODUCT INFOS") { ?>

				 <select  class="form-control" name="select" id="select<?php echo mt_rand(5, 15); ?>" style="width: 150px" >
                    <?php if (count($value['values'])>0){  

				    foreach ($value['values'] as $valores_default){ ?>
                    <option><?php echo trim($valores_default); ?></option>
                    <?php } ?>
                    <?php }else{ ?>
                    <option> <?php echo sprintf($text_none,$value['what']);?></option>
                    <?php } ?>
                  </select>
                  <?php }else{ ?>
					  <?php foreach ($languages as $language) { ?>
					  <div class="input-group">
						<span class="input-group-addon"> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />	</span> 
					  <select  class="form-control" name="select" id="select<?php echo mt_rand(5, 15); ?>" style="width: 150px" >
						<?php  if ($value['values'][$language['language_id']]){ 
						  natsort($value['values'][$language['language_id']]);
						  foreach ($value['values'][$language['language_id']] as $valores_default){ ?>
						<option><?php echo trim($valores_default); ?></option>
						<?php } ?>
						<?php }else{ ?>
						<option> <?php echo sprintf($text_none,$value['what']);?></option>
						<?php } ?>
					  </select>
				  <?php } ?>
					  </div>
                  <?php } ?>
				  </td>
              </tr>
              <tr class="tr_txtinfo_<?php echo $i; ?>" style="display:none;">
                <td>&nbsp;</td>
                <td colspan="8" align="left">
				<?php foreach ($languages as $language){?>
				
				  <div class="input-group">
							<span class="input-group-addon"> <img src="view/image/flags/<?php echo $language['image']; ?>" /></span>
							<textarea class="form-control" id="diveditor_<?php echo $i; ?>_<?php echo $language['language_id']; ?>">
							<?php echo isset($value['text_info'][$language['language_id']]) ? html_entity_decode($value['text_info'][$language['language_id']], ENT_QUOTES, 'UTF-8') : ''; ?> 
							</textarea>
				  </div>
				  
				  <?php } ?>
					  </td>
                <td valign="bottom"><a onclick="$('tr.tr_txtinfo_<?php echo $i; ?>').hide();" class="button btn btn-warning"><span><i class="fa fa-times"></i>&nbsp;<?php echo $button_close; ?></span></a></td>
              </tr>
              <?php $i++; } ?>
            </tbody>
          </table>
		  </div>
          <?php } ?>
        </div>
	     
        <div class="tab-pane" id="tab-ProductInfos">
		<div class="table-responsive">
          <table id="tblproductInfos" class="table table-bordered table-hover" style="font-size:12px !important">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_value; ?></td>
                <td class="text-left"><?php echo $entry_num_products; ?></td>
                <td class="text-left"><?php echo $entry_sort_order; ?></td>
                <td class="text-left"><?php echo $entry_view; ?></td>
                <td class="text-left"><?php echo $entry_unit; ?></td>
                <td class="text-left"><?php echo $text_info; ?></td>
                <td class="text-left"><?php echo $entry_search; ?></td>
                <td class="text-left"><?php echo $entry_open; ?></td>
                <td class="text-left"><?php echo $entry_order; ?></td>
                <td class="text-left"><?php echo $entry_examples; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($category['productinfo'] as $value) { ?>
              <tr>
                <td class="text-left"><?php $str="productinfo_id"; 

				if ($value['checked']) { ?>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][<?php echo $str; ?>]" type="checkbox" value="<?php echo $value[$str]; ?>" checked="checked" />
                  <?php echo $value['name']; ?>
                  <?php }else{ ?>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][<?php echo $str; ?>]" type="checkbox" value="<?php echo $value[$str]; ?>" />
                  <?php echo $value['name']; ?>
                  <?php } ?>
                  <br />
                  <span class="help"><?php echo substr(strtoupper($value['what']), 0, -1); ?></span>
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][name]" type="hidden" value="<?php echo $value['name']; ?>" />
                  <input name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][short_name]" type="hidden" value="<?php echo $value['short_name']; ?>" /></td>
				  
				  
					<?php foreach ($languages as $language){?>
					<input type= "hidden" id="editor_<?php echo $i; ?>_<?php echo $language['language_id']; ?>" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][text_info][<?php echo $language['language_id']; ?>]" value="<?php echo isset($value['text_info'][$language['language_id']]) ? $value['text_info'][$language['language_id']] : ''; ?>" />
					<?php } ?>
					
					
                <td class="text-left">
				<input class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][number]" type="text" value="<?php echo $value['number']; ?>" size="2"/></td>
                <td class="text-left"><input  class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][sort_order]" type="text" value="<?php echo $value['sort_order']; ?>" size="3"/></td>
                <td class="text-left"><select  class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][view]">
                    <?php if ($value['view'] == "slider") { ?>
                    <option value="slider" selected="selected"><?php echo $entry_slider; ?></option>
                    <?php } else { ?>
                    <option value="slider"><?php echo $entry_slider; ?></option>
                    <?php } ?>
                    <?php if ($value['view'] == "list") { ?>
                    <option value="list" selected="selected"><?php echo $entry_list; ?></option>
                    <?php } else { ?>
                    <option value="list"><?php echo $entry_list; ?></option>
                    <?php } ?>
                    <?php if ($value['view'] == "sele") { ?>
                    <option value="sele" selected="selected"><?php echo $entry_select; ?></option>
                    <?php } else { ?>
                    <option value="sele"><?php echo $entry_select; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left">
				<input  class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][unit]" type="text" value="<?php echo $value['unit']; ?>" size="5"/></td>
             
              
                   
				   
				     <td class="text-left">
				  <div class="input-group">
					<select class="infotext form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][info]">
                    <?php if ($value['info'] == "yes") { ?>
                    <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                    <?php } else { ?>
                    <option value="yes"><?php echo $text_yes; ?></option>
                    <?php } ?>
                    <?php if ($value['info'] == "no") { ?>
                    <option value="no" selected="selected"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="no"><?php echo $text_no; ?></option>
                    <?php } ?>
					</select>
					<span class="input-group-btn">
					   <?php if ($value['info'] == "yes") { ?>
						<a rel="<?php echo $i; ?>" class="edit_info_link btn btn-info " type="button"><i class="fa fa-keyboard-o"></i></a>
					   <?php } else { ?>
						<a rel="<?php echo $i; ?>"  class="edit_info_link btn btn-danger disabled" type="button"><i class="fa fa-keyboard-o"></i></a>
					   <?php } ?>
					</span>
					</div>
				 
				  </td>
				   
				   
				   
				   
				   
				   
				   
				   
				   
				   
				   <td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][searchinput]">
                    <?php if ($value['searchinput'] == "yes") { ?>
                    <option value="yes" selected="selected"><?php echo $text_yes; ?></option>
                    <?php } else { ?>
                    <option value="yes"><?php echo $text_yes; ?></option>
                    <?php } ?>
                    <?php if ($value['searchinput'] == "no") { ?>
                    <option value="no" selected="selected"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="no"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][initval]">
                    <?php if ($value['initval'] == "opened") { ?>
                    <option value="opened" selected="selected"><?php echo $text_open; ?></option>
                    <?php } else { ?>
                    <option value="opened"><?php echo $text_open; ?></option>
                    <?php } ?>
                    <?php if ($value['initval'] == "closed") { ?>
                    <option value="closed" selected="selected"><?php echo $text_close; ?></option>
                    <?php } else { ?>
                    <option value="closed"><?php echo $text_close; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left">
				
				<select class="form-control" name="VALORES_<?php echo $category['category_id']; ?>[<?php echo $value['what']; ?>][<?php echo $value[$str]; ?>][orderval]">
                    <?php if ($value['orderval'] == "OHASC") { ?>
                    <option value="OHASC" selected="selected"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OHASC"><?php echo $text_human; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OHDESC") { ?>
                    <option value="OHDESC" selected="selected"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OHDESC"><?php echo $text_human; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OTASC") { ?>
                    <option value="OTASC" selected="selected"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OTASC"><?php echo $text_count; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OTDESC") { ?>
                    <option value="OTDESC" selected="selected"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OTDESC"><?php echo $text_count; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OCOASC") { ?>
                    <option value="OCOASC" selected="selected"><?php echo $text_computer; ?> <?php echo $ASC; ?></option>
                    <?php } else { ?>
                    <option value="OCOASC"><?php echo $text_computer; ?> <?php echo $ASC; ?></option>
                    <?php } ?>
                    <?php if ($value['orderval'] == "OCODESC") { ?>
                    <option value="OCODESC" selected="selected"><?php echo $text_computer; ?> <?php echo $DESC; ?></option>
                    <?php } else { ?>
                    <option value="OCODESC"><?php echo $text_computer; ?> <?php echo $DESC; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select class="form-control" name="select" id="select<?php echo mt_rand(5, 15); ?>">
                    <?php if (count($value['values'])>0){  

				foreach ($value['values'] as $valores_default){ ?>
                    <option><?php echo trim($valores_default); ?></option>
                    <?php } ?>
                    <?php }else{ ?>
                    <option> <?php echo sprintf($text_none,$value['what']);?></option>
                    <?php } ?>
                  </select></td>
              </tr>
 		  <tr class="tr_txtinfo_<?php echo $i; ?>" style="display:none;">
                <td>&nbsp;</td>
                <td colspan="8" align="left">
				<?php foreach ($languages as $language){?>
				
				  <div class="input-group">
							<span class="input-group-addon"> <img src="view/image/flags/<?php echo $language['image']; ?>" /></span>
							<textarea class="form-control" id="diveditor_<?php echo $i; ?>_<?php echo $language['language_id']; ?>">
							<?php echo isset($value['text_info'][$language['language_id']]) ? html_entity_decode($value['text_info'][$language['language_id']], ENT_QUOTES, 'UTF-8') : ''; ?> 
							</textarea>
				  </div>
				  
				  <?php } ?>
					  </td>
                <td valign="bottom"><a onclick="$('tr.tr_txtinfo_<?php echo $i; ?>').hide();" class="button btn btn-warning"><span><i class="fa fa-times"></i>&nbsp;<?php echo $button_close; ?></span></a></td>
              </tr>
			  <?php $i++; } ?>
            </tbody>
          </table>
        </div>
		</div>
      </div>
    </form>
  </div>
</div>
</div>
<?php } ?>
<script type="text/javascript"><!--

	$( "a#setallcategories" ).on( "click", function() {	
	    $.ajax({
	    success :editsucces2,  // post-submit callback 
		url: "index.php?route=module/supercategorymenuadvanced/SetAllCategories&token=<?php echo $token; ?>",
		data: $('#categories').serialize(),
		type: "POST",
		cache: true,
		error: function(xhr, ajaxOptions, thrownError){
			alert('responseText: \n' + thrownError +'.'); }
		});
	});
	
	$( "a#editvalues" ).on( "click", function() {	
		$.ajax({
	    success :editsucces2,  // post-submit callback 
		url: "index.php?route=module/supercategorymenuadvanced/SetAllValues&token=<?php echo $token; ?>",
		data: $('#categories').serialize(),
		type: "POST",
		cache: true,
		error: function(xhr, ajaxOptions, thrownError){
			alert('responseText: \n' + thrownError +'.'); }
		});
	});



	function editsucces2(responseText, statusText, xhr){
		$("#notification2").html('<div style="display:none;" class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
   		$('.alert-success').fadeIn("slow");
   		$('.alert-success').delay(3500).fadeOut('slow');
    }

	$('a#editallcategories').click(function() {
		$.ajax({
		success :editsucces2,  // post-submit callback 
		url: "index.php?route=module/supercategorymenuadvanced/SetAllValuesCategories&token=<?php echo $token; ?>",
		data: $('#categories').serialize(),
		type: "POST",
		cache: true,
		error: function(xhr, ajaxOptions, thrownError){
			alert('responseText: \n' + thrownError +'.'); }
		});
	});

	$("img.tooltip").tooltip({
		track: true, 
	    delay: 0, 
	    showURL: false, 
	    showBody: " - ", 
	    fade: 250 
	});

	$('#tblattributes,#tbloptions,#tblproductInfos').on('change',':checkbox',  function() {
		obj=$(this).closest('tr').find('input:text');
		obj2=$(this).closest('tr').find('select[name*="VALORES"]');   
		obj3=$(this).closest('tr').find('input:hidden');
		obj4=$(this).closest('tr').next().find('textarea');
			if (this.checked) {
				obj.removeAttr('disabled'); obj2.removeAttr('disabled'); obj3.removeAttr('disabled'); obj4.removeAttr('disabled');
				obj.css({'background-color': '#EAF7D9','border': '1px solid #BBDF8D','color': '#555555', });
				obj2.css({'background-color': '#EAF7D9','border': '1px solid #BBDF8D','color': '#555555', });
			}else{
				obj3.attr('disabled', true);
				obj2.attr('disabled', true); 
				obj.attr('disabled', true); 
				obj4.attr('disabled', true);
				obj.css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': '#555555',});
				obj2.css({'background-color': '#FFD1D1','border': '1px solid #F8ACAC','color': '#555555',});
				$(this).closest('tr').find('input:text:eq(0)').val('8');  
				$(this).closest('tr').find('input:text:eq(1)').val('0');  
				$(this).closest('tr').find('input:text:eq(3)').val('no');
			}
	 $('input:checkbox').attr('disabled', false);
	});

$(':checkbox').change(); //set initially


$('.infotext').change(function() {
	obj=$(this).closest('td').find('a');
	value=$(this).val();
		if (value=="yes"){ 
		obj.removeClass('disabled').removeClass('btn-danger').addClass('btn-info');
		}else{
		obj.removeClass('btn-info').addClass('btn-danger').addClass('disabled');
		}
});

$('a.edit_info_link').click(function() {		
	var wrt=$(this).attr('rel');
		$(this).closest('tr').next().show();
			<?php foreach ($languages as $language){?>
			$('#diveditor_'+wrt+'_<?php echo $language['language_id']; ?>').destroy();
			$('#diveditor_'+wrt+'_<?php echo $language['language_id']; ?>').summernote({
				height: 300,
				onInit: function() {
					$('#editor_'+wrt+'_<?php echo $language['language_id']; ?>').val('');   
				},
				onblur: function(e) {
					var sHTML = $('#diveditor_'+wrt+'_<?php echo $language['language_id']; ?>').code();
					$('#editor_'+wrt+'_<?php echo $language['language_id']; ?>').val(sHTML);
				}
			});   
			<?php } ?>
});

function Selecbox2(me){
  	var dnd = me.name;
	var checked_status = me.checked;
        $('input[name^='+dnd+'_2]:checkbox').each(function(){
              this.checked = checked_status;
       });
	if (checked_status == true) {
		$('#tabvalues,#tblproductInfos thead ,#tbloptions thead').hide();
		$('#tab-attributes,#tab-options,#tab-ProductInfos').show();
    } else {
		$('#tabvalues,#tblproductInfos thead ,#tbloptions thead').show();
		$('#tabvalues a').tabs();
    }
} 
//--></script> 