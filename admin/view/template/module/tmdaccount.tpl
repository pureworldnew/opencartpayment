<?php echo $header; ?>
<script src="view/javascript/bootstrap/js/bootstrap-switch.js"></script>
<script src="view/javascript/bootstrap/js/highlight.js"></script>
<script src="view/javascript/bootstrap/js/bootstrap-switch.js"></script>
<script src="view/javascript/bootstrap/js/main.js"></script>
<link href="view/javascript/bootstrap/css/bootstrap-switch.css" rel="stylesheet">
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<button type="submit" form="form" onclick="$('#form-tmdaccount').attr('action','<?php echo $staysave; ?>');$('#form-tmdaccount').submit(); " data-toggle="tooltip" title="<?php echo $button_stay; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_stay; ?></button>	
        <button type="submit" form="form-tmdaccount" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tmdaccount" class="form-horizontal">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
				<li><a href="#tab-modulesetting" data-toggle="tab"><?php echo $tab_modulesetting; ?></a></li>
				<li><a href="#tab-sidebarmodule" data-toggle="tab"><?php echo $tab_sidebarmodule; ?></a></li>
				<li><a href="#tab-language" data-toggle="tab"><?php echo $tab_language; ?></a></li>
				<li><a href="#tab-custom_css" data-toggle="tab"><?php echo $tab_Custom; ?></a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab-general">
				
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
						  <select name="tmdaccount_status" id="input-status" class="form-control">
							<?php if ($tmdaccount_status) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						  </select>
						</div>
					</div>
					
					
					<div class="form-group hide">
						<label class="col-sm-2 control-label"><?php echo $entry_bgimage; ?></label>
						<div class="col-sm-2"><a href="" id="thumb-image1" data-toggle="image" class="img-thumbnail"><img src="<?php echo $tmdaccount_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
						  <input type="hidden" name="tmdaccount_bgimage" value="<?php echo $tmdaccount_bgimage; ?>" id="input-image1" />
						</div>
					
						<div class="col-sm-8">
							
							<div class="row">	
								<div class="col-sm-6 paddleft">
									<label class="control-label"><?php echo $entry_width; ?></label>
									<br/>	
									<input name="tmdaccount_bgwidth" value="<?php echo $tmdaccount_bgwidth; ?>" placeholder="Width" id="input-bg-width" class="form-control" type="text">
								</div>
								<div class="col-sm-6 paddleft">
									<label class="control-label"><?php echo $entry_height; ?></label>	
									<br/>
									<input name="tmdaccount_bgheight" value="<?php echo $tmdaccount_bgheight; ?>" placeholder="height" id="input-bg-height" class="form-control" type="text">
								</div>
							</div>
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_defaultimage; ?></label>
						<div class="col-sm-2"><a href="" id="thumb-defaultimage" data-toggle="image" class="img-thumbnail"><img src="<?php echo $tmdaccount_defaultpic; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
						  <input type="hidden" name="tmdaccount_defaultimage" value="<?php echo $tmdaccount_defaultimage; ?>" id="input-defaultimage" />
						</div>
					
						<div class="col-sm-8">
							<div class="row">	
								<div class="col-sm-6 paddleft">
									<label class="control-label"><?php echo $entry_width; ?></label>
									<br/>	
									<input name="tmdaccount_defaultwidth" value="<?php echo $tmdaccount_defaultwidth; ?>" placeholder="Width" id="input-default-width" class="form-control" type="text">
								</div>
								<div class="col-sm-6 paddleft">
									<label class="control-label"><?php echo $entry_height; ?></label>	
									<br/>
									<input name="tmdaccount_defaultheight" value="<?php echo $tmdaccount_defaultheight; ?>" placeholder="height" id="input-default-height" class="form-control" type="text">
								</div>
							</div>
							
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_midbgcolor; ?></label>
						<div class="col-sm-6">
							<input type="text" name="tmdaccount_midbgcolor" value="<?php echo $tmdaccount_midbgcolor; ?>"  id="input-midbgcolor" class="form-control color" >
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_primrybutoncolor; ?></label>
						<div class="col-sm-6">
							<input type="text" name="tmdaccount_pbtncolor" value="<?php echo $tmdaccount_pbtncolor; ?>"  id="input-pbtncolor" class="form-control color" >
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_primrybutontextcolor; ?></label>
						<div class="col-sm-6">
							<input type="text" name="tmdaccount_pbtntextcolor" value="<?php echo $tmdaccount_pbtntextcolor; ?>"  id="input-pbtntextcolor" class="form-control color" >
						</div>
					</div>
					
				</div>
				<div class="tab-pane" id="tab-sidebarmodule">
				
				<legend><?php echo $text_accountsetting; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_login; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_login" value="1" <?php if ($tmdaccount_login) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_register; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_register" value="1" <?php if ($tmdaccount_register) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_forgot; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_forgot" value="1" <?php if ($tmdaccount_forgot) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_myaccount; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_myaccount" value="1" <?php if ($tmdaccount_myaccount) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_editaccount; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_editaccount" value="1" <?php if ($tmdaccount_editaccount) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_password; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_password" value="1" <?php if ($tmdaccount_password) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_address_book; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_address_book" value="1" <?php if ($tmdaccount_address_book) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_wishlist; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_wishlist" value="1" <?php if ($tmdaccount_wishlist) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_newsletter; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_newsletter" value="1" <?php if ($tmdaccount_newsletter) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_logout; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_logout" value="1" <?php if ($tmdaccount_logout) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<legend><?php echo $text_ordersetting; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_order; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_order" value="1" <?php if ($tmdaccount_order) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_downloads; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_downloads" value="1" <?php if ($tmdaccount_downloads) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_payments; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_payments" value="1" <?php if ($tmdaccount_payments) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_reward; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_reward" value="1" <?php if ($tmdaccount_reward) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_returns; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_returns" value="1" <?php if ($tmdaccount_returns) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_transaction; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_transaction" value="1" <?php if ($tmdaccount_transaction) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<legend><?php echo $text_colorsetting; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_sidebarbg; ?></label>
						<div class="col-sm-4">
							<input type="text" name="tmdaccount_sidebarbg" value="<?php echo $tmdaccount_sidebarbg; ?>"  id="input-sidebarbg" class="form-control color" >
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_sidebarcolor; ?></label>
						<div class="col-sm-4">
							<input type="text" name="tmdaccount_sidebarcolor" value="<?php echo $tmdaccount_sidebarcolor; ?>"  id="input-sidebarcolor" class="form-control color" >
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_sidebarhover; ?></label>
						<div class="col-sm-4">
							<input type="text" name="tmdaccount_sidebarhover" value="<?php echo $tmdaccount_sidebarhover; ?>"  id="input-sidebarhover" class="form-control color" >
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_sidebarbotomborder; ?></label>
						<div class="col-sm-4">
							<input type="text" name="tmdaccount_sidebarbotomborder" value="<?php echo $tmdaccount_sidebarbotomborder; ?>"  id="input-sidebarbotomborder" class="form-control color" >
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_sidebarleftborder; ?></label>
						<div class="col-sm-4">
							<input type="text" name="tmdaccount_sidebarleftborder" value="<?php echo $tmdaccount_sidebarleftborder; ?>"  id="input-sidebarleftborder" class="form-control color" >
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-modulesetting">
					<div class="form-group">
						<div class="col-sm-3">
							<label class="col-sm-7 control-label"><?php echo $entry_totalorders; ?></label>
							<div class="col-sm-5">
								<input type="radio" name="tmdaccount_totalorders" value="1" <?php if ($tmdaccount_totalorders) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />	
							</div>
						</div>
						<div class="col-sm-9">
							<label class="col-sm-3 control-label"><?php echo $entry_totalorders_bg; ?></label>
							<div class="col-sm-4">
								<input type="text" name="tmdaccount_totalorders_bg" value="<?php echo $tmdaccount_totalorders_bg; ?>"  id="input-totalorders_bg" class="form-control color" >
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-3">
							<label class="col-sm-7 control-label"><?php echo $entry_totalwishlist; ?></label>
							<div class="col-sm-5">
								<input type="radio" name="tmdaccount_totalwishlist" value="1" <?php if ($tmdaccount_totalwishlist) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />	
							</div>
						</div>
						<div class="col-sm-9">
							<label class="col-sm-3 control-label"><?php echo $entry_totalwishlist_bg; ?></label>
							<div class="col-sm-4">
								<input type="text" name="tmdaccount_totalwishlist_bg" value="<?php echo $tmdaccount_totalwishlist_bg; ?>"  id="input-totalwishlist_bg" class="form-control color" >
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-3">
							<label class="col-sm-7 control-label"><?php echo $entry_totalreward; ?></label>
							<div class="col-sm-5">
								<input type="radio" name="tmdaccount_totalreward" value="1" <?php if ($tmdaccount_totalreward) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />	
							</div>
						</div>
						<div class="col-sm-9">
							<label class="col-sm-3 control-label"><?php echo $entry_totalreward_bg; ?></label>
							<div class="col-sm-4">
								<input type="text" name="tmdaccount_totalreward_bg" value="<?php echo $tmdaccount_totalreward_bg; ?>"  id="input-totalreward_bg" class="form-control color" >
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-3">
							<label class="col-sm-7 control-label"><?php echo $entry_totaldownload; ?></label>
							<div class="col-sm-5">
								<input type="radio" name="tmdaccount_totaldownload" value="1" <?php if ($tmdaccount_totaldownload) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />	
							</div>
						</div>
						<div class="col-sm-9">
							<label class="col-sm-3 control-label"><?php echo $entry_totaldownload_bg; ?></label>
							<div class="col-sm-4">
								<input type="text" name="tmdaccount_totaldownload_bg" value="<?php echo $tmdaccount_totaldownload_bg; ?>"  id="input-totaldownload_bg" class="form-control color" >
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-3">
							<label class="col-sm-7 control-label"><?php echo $entry_totaltransaction; ?></label>
							<div class="col-sm-5">
								<input type="radio" name="tmdaccount_totaltransaction" value="1" <?php if ($tmdaccount_totaltransaction) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />		
							</div>
						</div>
						<div class="col-sm-9">
							<label class="col-sm-3 control-label"><?php echo $entry_totaltransaction_bg; ?></label>
							<div class="col-sm-4">
								<input type="text" name="tmdaccount_totaltransaction_bg" value="<?php echo $tmdaccount_totaltransaction_bg; ?>"  id="input-totaltransaction_bg" class="form-control color" >
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-3">
							<label class="col-sm-7 control-label"><?php echo $entry_latestorder; ?></label>
							<div class="col-sm-5">
								<input type="radio" name="tmdaccount_latestorder" value="1" <?php if ($tmdaccount_latestorder) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />	
							</div>
						</div>
						<div class="col-sm-9">
							<label class="col-sm-3 control-label"><?php echo $entry_latestorder_bg; ?></label>
							<div class="col-sm-4">
								<input type="text" name="tmdaccount_latestorder_bg" value="<?php echo $tmdaccount_latestorder_bg; ?>"  id="input-latestorder_bg" class="form-control color" >
							</div>
						</div>
					</div>
					
					<legend><?php echo $entry_accountlink; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_editaccount; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_editaccount" value="1" <?php if ($tmdaccount_link_editaccount) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_password; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_password" value="1" <?php if ($tmdaccount_link_password) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_address_book; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_address_book" value="1" <?php if ($tmdaccount_link_address_book) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_wishlist; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_wishlist" value="1" <?php if ($tmdaccount_link_wishlist) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_order; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_order" value="1" <?php if ($tmdaccount_link_order) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_downloads; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_downloads" value="1" <?php if ($tmdaccount_link_downloads) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_reward; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_reward" value="1" <?php if ($tmdaccount_link_reward) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_returns; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_returns" value="1" <?php if ($tmdaccount_link_returns) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_transaction; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_transaction" value="1" <?php if ($tmdaccount_link_transaction) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_newsletter; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_newsletter" value="1" <?php if ($tmdaccount_link_newsletter) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_payments; ?></label>
						<div class="col-sm-10">
							<input type="radio" name="tmdaccount_link_payments" value="1" <?php if ($tmdaccount_link_payments) { ?> checked <?php } ?>data-radio-all-off="true" class="switch-radio2"  />					
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-language"> 
					<legend><?php echo $text_changelabel; ?></legend>
					<ul class="nav nav-tabs" id="language">
					  <?php foreach ($languages as $language) { ?>
					  <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
					  <?php } ?>
					</ul>
					<div class="tab-content">
					<?php foreach ($languages as $language) { ?>
						<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_profilelabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][profilelabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['profilelabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_checklabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][checklabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['checklabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_accountlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][accountlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['accountlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_editaclabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][editaclabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['editaclabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_passlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][passlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['passlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_booklabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][booklabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['booklabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_wishlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][wishlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['wishlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_orderlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][orderlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['orderlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_downlodlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][downlodlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['downlodlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_pointslabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][pointslabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['pointslabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_returnlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][returnlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['returnlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_transctionlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][transctionlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['transctionlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_newslabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][newslabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['newslabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_paylabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][paylabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['paylabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_totalodrlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][totalodrlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['totalodrlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_totalwishlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][totalwishlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['totalwishlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_totalpointlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][totalpointlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['totalpointlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_totaldownlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][totaldownlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['totaldownlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_totaltranslabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][totaltranslabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['totaltranslabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_latestlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][latestlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['latestlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_orderidlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][orderidlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['orderidlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_noprolabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][noprolabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['noprolabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_statuslabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][statuslabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['statuslabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_totalprolabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][totalprolabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['totalprolabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_datelabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][datelabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['datelabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_actionlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][actionlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['actionlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_loginlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][loginlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['loginlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_registerlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][registerlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['registerlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_forgotlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][forgotlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['forgotlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_logoutlabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][logoutlabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['logoutlabel'] : ''; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_viewalllabel; ?></label>
								<div class="col-sm-10">
									<input class="form-control" type="text" name="tmdaccount_lable[<?php echo $language['language_id']; ?>][viewalllabel]" size="100" value="<?php echo isset($tmdaccount_lable[$language['language_id']]) ? $tmdaccount_lable[$language['language_id']]['viewalllabel'] : ''; ?>" />
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="tab-pane" id="tab-custom_css"> 
	  			<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $entry_custom_css; ?></label>
					<div class="col-sm-10">
						<textarea name="tmdaccount_custom_css" placeholder="<?php echo $entry_custom_css; ?>" value="" id="input-custom_css" class="form-control" rows="8"><?php echo $tmdaccount_custom_css; ?></textarea>

					</div>
				</div> 
	  		</div>	
			</div>
		</form>
      </div>
    </div>
  </div>
</div>
<script src="view/javascript/colorbox/jquery.minicolors.js"></script>
<link rel="stylesheet" href="view/stylesheet/jquery.minicolors.css">
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<script>
$(document).ready( function() {
	$('.color').each( function() {
		$(this).minicolors({
			control: $(this).attr('data-control') || 'hue',
			defaultValue: $(this).attr('data-defaultValue') || '',
			inline: $(this).attr('data-inline') === 'true',
			letterCase: $(this).attr('data-letterCase') || 'lowercase',
			opacity: $(this).attr('data-opacity'),
			position: $(this).attr('data-position') || 'bottom left',
			change: function(hex, opacity) {
				if( !hex ) return;
				if( opacity ) hex += ', ' + opacity;
				try {
					console.log(hex);
				} catch(e) {}
			},
				theme: 'bootstrap'
		});
    });
});
</script>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>	
<style>
.minicolors-theme-bootstrap .minicolors-input{width:100%; height:35px;}
.paddleft{padding-left:0px;}
.iconcolor{color:#23a1d1!important;font-size:12px}
</style>
<?php echo $footer; ?>
