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
            <td class="left">Canonical Links For Categories</td>
           	<td class="left">
				<?php if (isset($canonicals['canonicals_categories'])) { ?>
                <input type="checkbox" name="canonicals[canonicals_categories]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="canonicals[canonicals_categories]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Canonical Links For Brands</td>
           	<td class="left">
				<?php if (isset($canonicals['canonicals_brands'])) { ?>
                <input type="checkbox" name="canonicals[canonicals_brands]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="canonicals[canonicals_brands]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Canonical Links For Info</td>
           	<td class="left">
				<?php if (isset($canonicals['canonicals_info'])) { ?>
                <input type="checkbox" name="canonicals[canonicals_info]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="canonicals[canonicals_info]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Canonical Links For Home Page</td>
           	<td class="left">
				<?php if (isset($canonicals['canonicals_home'])) { ?>
                <input type="checkbox" name="canonicals[canonicals_home]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="canonicals[canonicals_home]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Canonical Links For Specials</td>
           	<td class="left">
				<?php if (isset($canonicals['canonicals_specials'])) { ?>
                <input type="checkbox" name="canonicals[canonicals_specials]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="canonicals[canonicals_specials]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Extended Canonical Links (for all other routes which don't have canonical links)</td>
           	<td class="left">
				<?php if (isset($canonicals['canonicals_extended'])) { ?>
                <input type="checkbox" name="canonicals[canonicals_extended]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="canonicals[canonicals_extended]" value="1" />
                <?php } ?></td>            
          </tr>
		  
        </tbody>
       
             
      </table>
    </form>
  </div>
</div>

<?php echo $footer; ?>