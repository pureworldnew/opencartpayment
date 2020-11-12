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
    <div class="buttons"><a onclick="$('#form').submit();" class="button">Save & Replace</a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
	  <table id="module" class="list">
        <thead>
          <tr>
            <td class="left">Replace <input type="text" name="seoreplacer[replace]" value="<?php echo ((isset($seoreplacer['replace']))?$seoreplacer['replace']:'');?>" size="30"/> with <input type="text" name="seoreplacer[replacewith]" value="<?php echo ((isset($seoreplacer['replacewith']))?$seoreplacer['replacewith']:'');?>" size="30"/> for 
				  <select name="seoreplacer[language_id]">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ((isset($seoreplacer['language_id'])) && ($language['language_id'] == $seoreplacer['language_id'])) { ?>
                  <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select> language</td>            			
          </tr>
        </thead>
		</table>
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left">Select SEO data</td>
            <td class="left">Enable</td>            			
          </tr>
        </thead>
        
        <tbody>
          <tr>
            <td class="left">Replace in meta keywords for products/categories/info/brands</td>
           	<td class="left">
				<?php if (isset($seoreplacer['metakeywords'])) { ?>
                <input type="checkbox" name="seoreplacer[metakeywords]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[metakeywords]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Replace in meta description for products/categories/info/brands</td>
           	<td class="left">
				<?php if (isset($seoreplacer['metadescription'])) { ?>
                <input type="checkbox" name="seoreplacer[metadescription]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[metadescription]" value="1" />
                <?php } ?></td>            
          </tr>	 	
		  <tr>
            <td class="left">Replace in custom alts attributes for products</td>
           	<td class="left">
				<?php if (isset($seoreplacer['customalts'])) { ?>
                <input type="checkbox" name="seoreplacer[customalts]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[customalts]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
		  <tr>
            <td class="left">Replace in custom h1 tags for products</td>
           	<td class="left">
				<?php if (isset($seoreplacer['customh1tags'])) { ?>
                <input type="checkbox" name="seoreplacer[customh1tags]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[customh1tags]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
		  <tr>
            <td class="left">Replace in custom h2 tags for products</td>
           	<td class="left">
				<?php if (isset($seoreplacer['customh2tags'])) { ?>
                <input type="checkbox" name="seoreplacer[customh2tags]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[customh2tags]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
		  <tr>
            <td class="left">Replace in custom image titles attributes for products</td>
           	<td class="left">
				<?php if (isset($seoreplacer['customimagetitles'])) { ?>
                <input type="checkbox" name="seoreplacer[customimagetitles]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[customimagetitles]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
		  <tr>
            <td class="left">Replace in custom meta titles for products/categories/info/brands</td>
           	<td class="left">
				<?php if (isset($seoreplacer['customtitles'])) { ?>
                <input type="checkbox" name="seoreplacer[customtitles]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[customtitles]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
		  <tr>
            <td class="left">Replace in product tags for products</td>
           	<td class="left">
				<?php if (isset($seoreplacer['producttags'])) { ?>
                <input type="checkbox" name="seoreplacer[producttags]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[producttags]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
		  <tr>
            <td class="left">Replace in SEO URLs (SEO Keywords) for products/categories/info/brands</td>
           	<td class="left">
				<?php if (isset($seoreplacer['seourls'])) { ?>
                <input type="checkbox" name="seoreplacer[seourls]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seoreplacer[seourls]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
		  
		  
        </tbody>
       
             
      </table>	  
    </form>
	<span style="color:red" class="help">* SEO Replacer Tool will alter your existing SEO data. Database backup is highly recommended before each usage.</span>
  </div>
</div>

<?php echo $footer; ?>