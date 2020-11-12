<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="OrderSuccessPage">
 <div class="page-header">
    <div class="container-fluid">
      <h1><i class="fa fa-th"></i>&nbsp;<?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
	<?php echo (empty($moduleData['LicensedOn'])) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNpc2Vuc2Vfc3VwcG9ydF0nKS50cmlnZ2VyKCdjbGljaycpIj5FbnRlciB5b3VyIGxpY2Vuc2UgY29kZTwvYT4NCiAgICA8L2Rpdj4=') : '' ?>
	<?php if ($error_warning) { ?>
		<div class="alert alert-danger autoSlideUp"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success autoSlideUp"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <script>$('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>
    <?php } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i>&nbsp;<span style="vertical-align:middle;font-weight:bold;">Module settings</span></h3>
            <div class="storeSwitcherWidget">
            	<div class="form-group">
                	<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;<?php echo $store['name']; if($store['store_id'] == 0) echo " <strong>".$text_default."</strong>"; ?>&nbsp;<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                	<ul class="dropdown-menu" role="menu">
                    	<?php foreach ($stores as $st) { ?>
                            <li><a href="index.php?route=module/<?php echo $moduleNameSmall; ?>&store_id=<?php echo $st['store_id'];?>&token=<?php echo $token; ?>"><?php echo $st['name']; ?></a></li>
                    	<?php } ?> 
                	</ul>
            	</div>
            </div>
        </div>
        <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 
                <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>" />
                <div class="tabbable">
                    <div class="tab-navigation form-inline">
                        <ul class="nav nav-tabs mainMenuTabs" id="mainTabs">
							<li><a href="#controlpanel" role="tab" data-toggle="tab"><i class="fa fa-power-off"></i>&nbsp;Control Panel</a></li>
                            <li><a href="#isense_support" role="tab" data-toggle="tab"><i class="fa fa-external-link"></i>&nbsp;Support</a></li>
                        </ul>
                        <div class="tab-buttons">
                            <button type="submit" class="btn btn-success save-changes"><i class="fa fa-check"></i>&nbsp;Save Changes</button>
                            <a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><i class="fa fa-times"></i>&nbsp;Cancel</a>
                        </div> 
                    </div><!-- /.tab-navigation --> 
                    <div class="tab-content">
                    	<?php
                        if (!function_exists('modification_vqmod')) {
                        	function modification_vqmod($file) {
                        		if (class_exists('VQMod')) {
                       				return VQMod::modCheck(modification($file), $file);
                        		} else {
                        			return modification($file);
                       			}
                        	}
                        }
						?>
						<div id="controlpanel" class="tab-pane"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/module/'.$moduleNameSmall.'/tab_controlpanel.php'); ?></div>
                        <div id="isense_support" class="tab-pane"><?php require_once modification_vqmod(DIR_APPLICATION.'view/template/module/'.$moduleNameSmall.'/tab_support.php'); ?></div>
                    </div> <!-- /.tab-content --> 
                </div><!-- /.tabbable -->
            </form>
        </div> 
    </div>
  </div>
</div>
<script>
$('#mainTabs a:first').tab('show'); // Select first tab
$('.module-list').children().first().children('a').click();
if (window.localStorage && window.localStorage['currentTab']) {
	$('.mainMenuTabs a[href="'+window.localStorage['currentTab']+'"]').tab('show');
}
if (window.localStorage && window.localStorage['currentSubTab']) {
	$('a[href="'+window.localStorage['currentSubTab']+'"]').tab('show');
}
$('.fadeInOnLoad').css('visibility','visible');
$('.mainMenuTabs a[data-toggle="tab"]').click(function() {
	if (window.localStorage) {
		window.localStorage['currentTab'] = $(this).attr('href');
	}
});
$('a[data-toggle="tab"]:not(.mainMenuTabs a[data-toggle="tab"], .module_tabs a[data-toggle="tab"], .moduledata_promotedproducts_tabs a[data-toggle="tab"])').click(function() {
	if (window.localStorage) {
		window.localStorage['currentSubTab'] = $(this).attr('href');
	}
});

$(function() {
	showHideStuff($('#Checker'), $('#module_tabs'), 'yes');
	showHideStuff($('#OrderProductsChecker'), $('.orderproductimages'), 'yes');  
	showHideStuff($('#OrderSharingChecker'), $('.addthisurl'), 'yes');
	
	if($('select[name="ordersuccesspage[DiscountType]"]').val() == 'P'){
		$('#percentageAddon').show();
	} else {
		$('#currencyAddon').show();
	}
	$('select[name="ordersuccesspage[DiscountType]').on('change', function(e){ 
		if($(this).val() == 'P') {
			$('#percentageAddon').show();
			$('#currencyAddon').hide();
		} else {
			$('#currencyAddon').show();
			$('#percentageAddon').hide();
		}	
		if($(this).val() == 'N') {
			$('.discountSettings').hide(300);
		} else {
			$('.discountSettings').show(300);
		}
	});
	if($('select[name="ordersuccesspage[DiscountType]"]').val() == 'N'){
		$('.discountSettings').hide();
	} else {
		$('.discountSettings').show();
	}	
});
 </script>
<?php echo $footer; ?>