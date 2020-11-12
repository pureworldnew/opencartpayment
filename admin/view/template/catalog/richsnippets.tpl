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
            <td class="left">Use Breadcrumbs RDF</td>
           	<td class="left">
				<?php if (isset($richsnippets['breadcrumbs'])) { ?>
                <input type="checkbox" name="richsnippets[breadcrumbs]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[breadcrumbs]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use Product Schema</a></td>
           	<td class="left">
				<?php if (isset($richsnippets['product'])) { ?>
                <input type="checkbox" name="richsnippets[product]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[product]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  <tr>
            <td class="left">Use Offer Schema</a></td>
           	<td class="left">
				<?php if (isset($richsnippets['offer'])) { ?>
                <input type="checkbox" name="richsnippets[offer]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[offer]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  <tr>
            <td class="left">Use Rating Schema</a></td>
           	<td class="left">
				<?php if (isset($richsnippets['rating'])) { ?>
                <input type="checkbox" name="richsnippets[rating]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="richsnippets[rating]" value="1" />
                <?php } ?></td>            
          </tr>	 
		  
        </tbody>
       
             
      </table>	  
    </form>
  </div>
</div>

<?php echo $footer; ?>