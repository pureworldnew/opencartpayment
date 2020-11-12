<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"><link type="text/css" href="view/stylesheet/stylesheet2.css" rel="stylesheet" media="screen" />
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
	<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>    
  </div>
  <div class="content">
  
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<ul class="nav nav-tabs">
            <li class="active"><a href="#tab-data" data-toggle="tab">Redirect Manager</a></li>
            <li><a href="#tab-auto" data-toggle="tab">Smart Auto Redirect Log</a></li>
            <li><a href="#tab-settings" data-toggle="tab">Settings</a></li>
          </ul>
	<div class="tab-content">
	<div class="tab-pane active" id="tab-data">
	<table id="redirect" class="list">
        <thead>
          <tr>
            <td class="left">Redirect from</td>
            <td class="left">Redirect to</td>
            <td></td>
          </tr>
        </thead>
        <?php $route_row = 0; ?>
        <?php foreach ($redirect as $route) { ?>
        <tbody id="route-row<?php echo $route_row; ?>">
          <tr>
            <td><input type="text" name="redirect[<?php echo $route_row; ?>][title]" value="<?php echo $route['title']; ?>" /></td>
            <td><input type="text" name="redirect[<?php echo $route_row; ?>][url]" value="<?php echo $route['url']; ?>" /></td>			 
            <td class="left"><a onclick="$('#route-row<?php echo $route_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
          </tr>
        </tbody>
        <?php $route_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="2"></td>
            <td class="left"><a onclick="addroute();" class="button"><?php /*echo $button_add_route; */?>Add Redirect</a></td>
          </tr>
        </tfoot>
      </table>
		</div>
	<div class="tab-pane" id="tab-auto">
			 <table class="list">
          <thead>
            <tr>              
              <td class="left">Link</td>
              <td class="left">Fixed Link</td>
              <td class="left">Date</td>              
            </tr>
          </thead>
          <tbody>
            
            <?php if (!empty($pages)) { ?>
            <?php foreach ($pages as $page) { ?>
            <tr>              
              <td class="left"><?php echo $page['link']; ?></td>
              <td class="left"><?php echo $page['fixedlink']; ?></td>
              <td class="left"><?php echo $page['date']; ?></td>                            			  
            </tr>
			<?php } ?>			
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
		 <div class="pagination"><?php echo $pagination; ?></div>
	</div>
	<div class="tab-pane" id="tab-settings">
	<table id="module" class="list">
        <thead>
          <tr>
            <td class="left">Feature</td>
            <td class="left">Enable</td>            			
          </tr>
        </thead>
        
        <tbody>
          <tr>
            <td class="left">Enable Redirect Manager which allows you to add redirect rules (eg. <b>oldproduct.html</b> -> <b>newproduct.html</b> will redirect <b>http://yoursite.com/oldproduct.html</b> to <b>http://yoursite.com/newproduct.html</b>)</td>
           	<td class="left">
				<?php if (isset($redirect_settings['redirectmanager'])) { ?>
                <input type="checkbox" name="redirect_settings[redirectmanager]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="redirect_settings[redirectmanager]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Enable Smart Auto Redirect which automatically redirects misspelled URLs (eg. <b>http://yoursite.com/aple</b> will be automatically redirected to <b>http://yoursite.com/apple</b> if http://yoursite.com/aple is not found)</td>
           	<td class="left">
				<?php if (isset($redirect_settings['autoredirect'])) { ?>
                <input type="checkbox" name="redirect_settings[autoredirect]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="redirect_settings[autoredirect]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  
        </tbody>
       
             
      </table>
	</div>
	</div>
    </form>
	<span style="color:red" class="help">*</span>	
  </div>
</div>
<script type="text/javascript"><!--

var route_row = <?php echo $route_row; ?>;

function addroute() {	
        
	html  = '<tbody id="route-row' + route_row + '">';
	html += '  <tr>';
	html += '    <td><input type="text" name="redirect[' + route_row + '][title]" value="" /></td>';
	html += '    <td><input type="text" name="redirect[' + route_row + '][url]" value="" /></td>';
	html += '    <td class="left"><a onclick="$(\'#route-row' + route_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#redirect tfoot').before(html);
	
	route_row++;
}

//--></script>
</script> 

<?php echo $footer; ?>