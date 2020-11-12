<?php 
if (!empty($values_no_selected)) { ?>
<?php foreach ($values_no_selected as $value_no_select) { ?>
<?php foreach ($value_no_select as $value_no_sel) { ?>
<?php 
	   /********* 
	   **** LIST VIEW CODE 
       *******************************/?>
<?php if ($value_no_sel['view']=="list"){ $i=rand(50,150000);?>

<div id="search_container_<?php echo $i; ?>" >
  <ul>
    <?php if ($value_no_sel['searchinput']=="yes"){ ?>
    <input name="" type="text"  id="search<?php echo $i; ?>" class="search-box-bg form-control"  onkeyup="refineResults(event,this,'search_container_<?php echo $i; ?>','#search_container_<?php echo $i; ?>')" value="<?php echo $search_in; ?>" onclick="this.value = '';" />
    <a href="javascript:void(0);" id="search_clear<?php echo $i; ?>" ></a>
    <?php } ?>
    <?php foreach ($value_no_sel['jurjur'] as $value){ 
      if($value['seleccionado']== "is_seleccionado") {
	  $count=$count_products ? "&nbsp;<span class=\"product-count\">(". $value['total'] .")</span>" : "";
     ?>
    <li class="active"><em>&nbsp;</em><a  class="link_filter_del custom_<?php echo $value['attribute_id'];?> smenu {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'no'}"  data-name="<?php echo $value['name'];?>" data-id="<?php echo $value['attribute_id'];?>" href="javascript:void(0)" <?php echo $nofollow; ?> ><img src="image/advancedmenu/spacer.gif" alt="<?php echo $remove_filter_text; ?>" class="filter_del" /></a><span><?php echo $value_no_sel['tipo']=="product_info" ? $value['val_formatted']: $value['name'];?><?php echo $count;?></span></li>
    <?php }else{ 
     ($count_products)? $count="&nbsp;<span class=\"product-count\">(". $value['total'] .")</span>" : $count="";
     ($track_google) ? $gap=trim($value_no_sel['name'])."@@@@@@".trim($value['name']) : $gap="no";
     ?>
    <li><em>&nbsp;</em><a class="smenu custom_add {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'<?php echo $gap; ?>'}" data-id="<?php echo $value['attribute_id'];?>" href="javascript:void(0)" <?php echo $nofollow; ?>><?php echo $value_no_sel['tipo']=="product_info" ? $value['val_formatted']: $value['name'];?></a><?php echo $count; ?></li>
    <?php } ?>
    <?php } //end of is_seleccionado ?>
  </ul>

</div>
<?php 
	   /********* 
	   **** OPTION VIEW IMAGE CODE 
       *******************************/?>
<?php }elseif ($value_no_sel['view']=="image") { ?>
<ul>
  <?php foreach ($value_no_sel['jurjur'] as $value){ ?>
  <?php if($value['seleccionado']== "is_seleccionado") {    
   ($count_products)? $count=" <span class=\"product-count\">(". $value['total'] .")</span>" : $count="";
   ($track_google) ? $gap=trim($value_no_sel['name'])."@@@@@@".trim($value['name']) : $gap="no";
  ?>
  <li class="active"> <img original-title="<?php echo $value['name']; ?>" src="<?php echo $value['image_thumb'];?>" alt="<?php echo utf8_strtoupper($value['name']); ?>" align="absmiddle" class="picker"/> <a class="link_filter_del smenu {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'<?php echo $gap; ?>'}" href="javascript:void(0)" <?php echo $nofollow; ?> > <img src="image/advancedmenu/spacer.gif" alt="<?php echo $remove_filter_text; ?>" class="filter_del" /></a> <span><?php echo $value['name'];?><?php echo $count; ?></span></li>
  <?php }else{ 
  
  ($track_google) ? $gap=trim($value_no_sel['name'])."@@@@@@".trim($value['name']) : $gap="no";
  
  ?>
  <a class="smenu {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'<?php echo $gap; ?>'}" href="javascript:void(0)" <?php echo $nofollow; ?>><img original-title="<?php echo $value['name']; ?>" class="picker" src="<?php echo $value['image_thumb'];?>" alt="<?php echo utf8_strtoupper($value['name']); ?>"/></a>
  <?php } ?>
  <?php } //end is_seleccionado ?>
</ul>
<?php 
	   /********* 
	   **** REVIEW VIEW IMAGE CODE 
       *******************************/?>
<?php }elseif ($value_no_sel['view']=="rimage") { ?>
<ul>
  <?php foreach ($value_no_sel['jurjur'] as $value){ 
   ($count_products)? $count=" <span class=\"product-count\">(". $value['total'] .")</span>" : $count="";
   ($track_google) ? $gap=trim($value_no_sel['name'])."@@@@@@".trim($value['name']) : $gap="no";
  
  ?>
  <?php if($value['seleccionado']== "is_seleccionado") {?>
  <li class="active rating" original-title="<?php echo  sprintf($re_extra_text,$value['name']); ?>" alt="<?php echo  sprintf($re_extra_text,$value['name']); ?>">
    <?php	for ($i = 1; $i <= 5; $i++) { 
            if ($value['name']< $i) { ?>
    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
    <?php   } else { ?>
    <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
    <?php  } 
            } ?>
    <a class="link_filter_del smenu {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'<?php echo $gap; ?>'}" href="javascript:void(0)" <?php echo $nofollow; ?> > <img src="image/advancedmenu/spacer.gif" alt="<?php echo $remove_filter_text; ?>" class="filter_del" /></a> <span><?php echo $count; ?></span></li>
  <?php }else{ ?>
  <li class="rating"><a class="smenu {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'<?php echo $gap; ?>'}" href="javascript:void(0)" <?php echo $nofollow; ?> original-title="<?php echo  sprintf($re_extra_text,$value['name']); ?>" alt="<?php echo  sprintf($re_extra_text,$value['name']); ?>" >
    <?php	for ($i = 1; $i <= 5; $i++) { 
            if ($value['name'] < $i) { ?>
    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
    <?php   } else { ?>
    <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
    <?php  } 
            } ?>
    <span><?php echo $count; ?></span></li>
  <?php } ?>
  <?php } //end is_seleccionado ?>
</ul>
<?php 
	   /********* 
	   ****  SELECT VIEW CODE
       *******************************/?>
<?php }elseif ($value_no_sel['view']=="sele") { ?>
<?php foreach ($value_no_sel['jurjur'] as $value){  ?>
<?php   if($value['seleccionado']== "is_seleccionado") { ?>
<a class="link_filter_del smenu {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'no'}" href="javascript:void(0)" <?php echo $nofollow; ?> ><img src="image/advancedmenu/spacer.gif" alt="<?php echo $remove_filter_text; ?>" class="filter_del" /></a>
<?php }?>
<?php }?>
<select class="smenu form-control" style="margin-left:5px;">
  <?php foreach ($value_no_sel['jurjur'] as $value){  ?>
  <?php ($value['seleccionado']== "is_seleccionado")  ?  $selected="selected" : $selected="";  
 	($count_products)? $count="&nbsp;(". $value['total'] .")" : $count="";
   	($track_google) ? $gap=trim($value_no_sel['name'])."@@@@@@".trim($value['name']) : $gap="no";  ?>
  <option class="smenu {dnd:'<?php echo $value['href'];?>', ajaxurl:'<?php echo $value['ajax_url'];?>', gapush:'<?php echo $gap; ?>'}" <?php echo $selected; ?> ><?php echo $value_no_sel['tipo']=="product_info" ? $value['val_formatted']: $value['name'];?><?php echo $count; ?></option>
  <?php } ?>
</select>
<?php }  ?>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript">
	<?php if ($option_tip){ ?>
	$('img.picker').tipsy({gravity: 's', fade: true}); // Added for Displaying Title of Adv. Layered Menu Images
	<?php } ?>
</script>