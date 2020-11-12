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
      
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left">Feature</td>
            <td class="left">Enable</td>            			
          </tr>
        </thead>
        
        <tbody>
          <tr>
            <td class="left">Add Virtual Subfolders for additional languages in SEO URLs (eg. http://yoursite.com/<b>fr/</b>/yourproduct.html</td>
           	<td class="left">
				<?php if (isset($mlseo['subfolder'])) { ?>
                <input type="checkbox" name="mlseo[subfolder]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="mlseo[subfolder]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use rel="alternate" hreflang="x" - read more about it <a href="https://support.google.com/webmasters/answer/189077?hl=en">here</a></td>
           	<td class="left">
				<?php if (isset($mlseo['hreflang'])) { ?>
                <input type="checkbox" name="mlseo[hreflang]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="mlseo[hreflang]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  
        </tbody>
       
             
      </table>
	  <span style="color:red" class="help">If your store is multi-language,  make sure you installed Multi-Language SEO URLs from for_multi_language_stores folder and
			set multi-language titles, meta keywords and meta descriptions for your home page in admin area -> System -> Settings -> Edit your store -> Store tab.</span>
    </form>
  </div>
</div>

<?php echo $footer; ?>