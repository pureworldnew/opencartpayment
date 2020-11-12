<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <div class="pull-right">
      <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo $button_save; ?></button>
      <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_exit; ?>" class="btn btn-danger"><i class="fa fa-reply"></i> <?php echo $button_exit; ?></a> <a onclick="location = '<?php echo $settings_btn; ?>'" data-toggle="tooltip" title="<?php echo $button_settings; ?>" class="btn btn-primary"><i class="fa fa-cog"></i> <?php echo $button_settings; ?></a> 
    </div>
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
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
<div class="panel panel-default">
<div class="panel-heading">
  <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
</div>
<div class="panel-body">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="formsettings" class="form-horizontal" >
<input name="token" type="hidden" value="<?php echo $token; ?>" />
<ul class="nav nav-tabs" id="principal" role="tablist">
  <?php $f=0; foreach ($stores as $store){ ?>
  <li <?php if(!$f) echo 'class="active"'; $f = 1; ?>>
  
  <a data-toggle="tab" href="#tab-store_id_<?php echo $store['store_id']; ?>"><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo $store['name']; ?></a></li>
  <?php  }  ?>
</ul>
<div class="tab-content">
  <?php $f=$e=0; foreach ($stores as $store){ ?>
  <style type="text/css">
        #tab-store_id_<?php echo $store['store_id']; ?> .info {border-color:#EB7213;}
        #tab-store_id_<?php echo $store['store_id']; ?> li a.info { border-color: #EB7213;background-color:#DDD074; }
        #tab-store_id_<?php echo $store['store_id']; ?> li.active a.info {border-bottom-color: transparent;background-color:#E6E7E9; }
        #tab-store_id_<?php echo $store['store_id']; ?> .tab-pane{ border:solid 1px #EB7213;  border-top: 0; background-color:#E6E7E9;}
		.smalltxt {color: #ffffff !important;font-size: 75%;}
		.backall{background: #373737 none repeat scroll 0 0 !important;
			border-color: #515151;
			color: #ffffff !important;    
		}
		.backvirtual{background: #515151 none repeat scroll 0 0 !important;
			border-color: #373737;
			color: #ffffff !important;
			
			};
		
		.backnormal();
		 
		
		
    </style>
  <div id="tab-store_id_<?php echo $store['store_id']; ?>" data-name="tab-store_id_<?php echo $store['store_id']; ?>" class="tab-pane <?php if(!$f) echo 'active'; $f = 1; ?>">
    <ul class="nav nav-tabs" id="principal2_<?php echo $store['store_id']; ?>" role="tablist">
      <li <?php if(!$e) echo 'class="active"'; $e = 1; ?>><a data-toggle="tab" href="#tab-category_id_s<?php echo $store['store_id']; ?>">Categories <?php echo $store['name']; ?></a></li>
      <li><a data-toggle="tab" href="#tab-manufacturer_id_s<?php echo $store['store_id']; ?>">Manufacturers <?php echo $store['name']; ?></a></li>
    </ul>
  </div>
  <?php  }  ?>
</div>
<div class="tab-content">
  <?php $f=0; foreach ($stores as $store){ ?>
  <div id="tab-category_id_s<?php echo $store['store_id']; ?>" data-name="tab-category_id_s<?php echo $store['store_id']; ?>" class="tab-pane <?php if(!$f) {echo ' active'; $f=1;} ?>">
    <?php if ($categories) {?>
    <div class="panel-group" id="accordion<?php echo $store['store_id']; ?>">
      <?php $i=0;      
      foreach ($categories['str'.$store['store_id']] as $category) { ?>
      <div class="panel panel-default">
        <div class="panel-heading <?php echo $category['class']; ?>" >
          <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion<?php echo $store['store_id']; ?>" href="#collapse_c<?php echo $i; ?><?php echo $store['store_id']; ?>"><?php echo $category['name']; ?></a></h4>
        </div>
        <div id="collapse_c<?php echo $i; ?><?php echo $store['store_id']; ?>" class="panel-collapse collapse">
          <div class="panel-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_name; ?></td>
                    <td class="text-Letf"><?php echo $column_action; ?></td>
                    <td class="text-right"><?php echo $entry_delete_cache_att; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($category['subcategories'] as $cat) { ?>
                  <tr id="<?php echo $cat['category_id']; ?>_<?php echo $store['store_id']; ?>">
                    <td class="text-left"><?php echo $cat['name']; ?></td>
                    <td class="text-left"><a class="edit" href="javascript:void(0)" store_id="<?php echo $store['store_id']; ?>" category_id="<?php echo $cat['category_id']; ?>" ><?php echo $entry_select_att; ?> <?php echo $cat['name']; ?></a></td>
                    <td class="text-right"><a class="delete btn btn-danger" href="index.php?route=module/supercategorymenuadvanced/DeleteCache&token=<?php echo $token; ?>&store_id=<?php echo $store['store_id']; ?>&category_id=<?php echo $cat['category_id']; ?>"><i class="fa fa-times"></i>&nbsp;<?php echo $entry_delete_cache_att; ?></a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
          </div>
        </div>
      </div>
      <?php $i++;  
                  } ?>
    </div>
    <div class="row" id="category_id_s<?php echo $store['store_id']; ?>" >
      <div class="col-sm-6 text-left"><?php echo ${"pagination_cat".$store['store_id']}; ?></div>
      <div class="col-sm-6 text-right"><?php echo ${"results_cat".$store['store_id']}; ?></div>
    </div>
    <?php } ?>
  </div>

  <div id="tab-manufacturer_id_s<?php echo $store['store_id']; ?>" class="tab-pane">
    <?php if ($manufacturers) {?>
    <div class="panel-group" id="accordion2<?php echo $store['store_id']; ?>">
      <?php $i=0;      
                   foreach ($manufacturers['str'.$store['store_id']] as $manufacturer) { ?>
      <div class="panel panel-default">
        <div class="panel-heading <?php echo $manufacturer['class']; ?>" >
          <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2<?php echo $store['store_id']; ?>" href="#collapse_m<?php echo $i; ?><?php echo $store['store_id']; ?>"><?php echo $manufacturer['name']; ?></a></h4>
        </div>
        <div id="collapse_m<?php echo $i; ?><?php echo $store['store_id']; ?>" class="panel-collapse collapse">
          <div class="panel-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_namem; ?></td>
                    <td class="text-Letf"><?php echo $column_action; ?></td>
                    <td class="text-right"><?php echo $entry_delete_cache_att; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($manufacturer['submanufacturer'] as $man) { ?>
                  <tr id="M<?php echo $man['manufacturer_id']; ?>_<?php echo $store['store_id']; ?>">
                    <td class="text-left"><?php echo $man['name']; ?></td>
                    <td class="text-left"><a class="edit_manu" href="javascript:void(0)" store_id="<?php echo $store['store_id']; ?>" manufacturer_id="<?php echo $man['manufacturer_id']; ?>" ><?php echo $entry_select_att; ?> <?php echo $man['name']; ?></a></td>
                    <td class="text-right"><a class="delete btn btn-danger" href="index.php?route=module/supercategorymenuadvanced/DeleteCacheManufacturer&token=<?php echo $token; ?>&store_id=<?php echo $store['store_id']; ?>&manufacturer_id=<?php echo $man['manufacturer_id']; ?>"><i class="fa fa-times"></i>&nbsp;<?php echo $entry_delete_cache_att; ?></a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
          </div>
        </div>
      </div>
      <?php $i++; } ?>
    </div>
    <div class="row" id="manufacturer_id_s<?php echo $store['store_id']; ?>" >
      <div class="col-sm-6 text-left"><?php echo ${"pagination_man".$store['store_id']}; ?></div>
      <div class="col-sm-6 text-right"><?php echo ${"results_man".$store['store_id']}; ?></div>
    </div>
    <?php } ?>
  </div>
  <?php } ?>
</div>
</form>
</div>
</div>
</div>
</div>
<!-- end panel-body -->

<script type="text/javascript"><!--
	function AjaxCategories(id,store){        
		$.ajax({
			success:showResponseAttributes,
			url: 'index.php?route=module/supercategorymenuadvanced/GetAllValues',
			data: 'token=<?php echo $token; ?>&category_id='+id+'&store_id='+store,
			type: "GET",
			cache: true,
			error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
      });};
	function AjaxManufactures(id,store){        
		$.ajax({
			success:showResponseManufactures,  // post-submit callback 
			url: 'index.php?route=module/supercategorymenuadvanced/GetAllValuesManufacturer',
			data: 'token=<?php echo $token; ?>&manufacturer_id='+id+'&store_id='+store,
			type: "GET",
			cache: true,
			error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		});
	};
	function showResponseManufactures(responseText, statusText, xhr)  { $('#valores_nuevos_manu').fadeOut('slow', function(){ $("#valores_nuevos_manu").html(''); $("#valores_nuevos_manu").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	function showResponseAttributes(responseText, statusText, xhr)  { $('#valores_nuevos').fadeOut('slow', function(){$("#valores_nuevos").html(''); $("#valores_nuevos").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	$('a.edit').click(function() {
		var id = $(this).attr("category_id"); var store =$(this).attr("store_id"); 
		$(this).after('&nbsp;<i class="fa fa-cog fa-spin"></i>');
		$('#tr_nueva').remove();
		html='<tr id="tr_nueva"><td class="center" colspan="7"><div id="valores_nuevos"></div></td></tr>';
		$('tr#'+id+"_"+store).after(html);
		AjaxCategories(id,store);
		return false;		
	});
	$('a.edit_manu').click(function() {
		var  id= $(this).attr("manufacturer_id"); 
		var store =$(this).attr("store_id"); 
		$(this).after('&nbsp;<i class="fa fa-cog fa-spin"></i>');
		$('#tr_nueva_m').remove();
		html='<tr id="tr_nueva_m"><td class="center" colspan="7"><div id="valores_nuevos_manu"></div></td></tr>';
		$('tr#M'+id+"_"+store).after(html);
		AjaxManufactures(id,store);
		return false;		
	});
   $('a.delete').fancybox({'type':'iframe','transitionIn':'elastic','transitionOut':'elastic','speedIn':600,'speedOut':200,'scrolling':'yes','showCloseButton':false,'overlayShow':false,'autoDimensions':false,'width': 800,'height': 700,});
<?php if ($error_warning) { ?>
   $("#notification").html('<div class="warning" style="display: none;"><?php echo $error_warning; ?></div>');
   $(".warning").fadeIn("slow");
   $('.warning').delay(2500).fadeOut('slow');
   $("html, body").animate({
         scrollTop: 0
     }, "slow")
<?php } ?>
<?php if ($success) { ?>
 $("#notification").html('<div class="success" style="display: none;"><?php echo $success; ?></div>');
   $(".success").fadeIn("slow");
   $('.success').delay(2500).fadeOut('slow');
   $("html, body").animate({
      scrollTop: 0
   }, "slow")
<?php } ?>
//--></script>
<script type="text/javascript"><!--
     <?php foreach ($stores as $store){ ?>    
	  $("div#category_id_s<?php echo $store['store_id']; ?> ul.pagination li ").on( "click","a",  function( event ) {
		event.preventDefault();
		var hreflink = $(this).attr("href");
	    $('#tab-category_id_s<?php echo $store['store_id']; ?>').load(hreflink);
		 });
	   $("div#manufacturer_id_s<?php echo $store['store_id']; ?> ul.pagination li ").on( "click","a",  function( event ) {
		event.preventDefault();
		var hreflink = $(this).attr("href");
	    $('#tab-manufacturer_id_s<?php echo $store['store_id']; ?>').load(hreflink);
		 });
	  <?php } ?> 
$('#principal a').click(function(e) {
	e.preventDefault()
	var step1=$(this).attr('href');
	var where_clicked=step1.slice(-1)
		
	$('div').find("[id^='tab-category_id_s']").each(function(){
			$(this).removeClass('active');
		});
	$('div').find("[id^='tab-manufacturer_id_s']").each(function(){
			$(this).removeClass('active');
		});
	
	$('ul.nav-tabs').find("li").each(function(){
			$(this).removeClass('active');
		});
	
	
	
	$('div#tab-category_id_s'+where_clicked).addClass('active');
	$('ul#principal2_'+where_clicked+' li').first().addClass('active');
	// $(this).tab('show')
	
}); 
//--></script> 
<script type="text/javascript"><!--
//$('#principal a:first').tab('show'); 
</script> 
<?php echo $footer; ?> 