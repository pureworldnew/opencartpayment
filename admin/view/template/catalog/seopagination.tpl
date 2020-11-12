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
            <td class="left">Use SEO Pagination (eg. yoursite.com/category/<b>page/3</b>)</td>
           	<td class="left">
				<?php if (isset($seopagination['pagination'])) { ?>
                <input type="checkbox" name="seopagination[pagination]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seopagination[pagination]" value="1" />
                <?php } ?></td>            
          </tr>
		  <tr>
            <td class="left">Use rel="prev" or rel="next"<br><br><span class="help">Much like rel=”canonical” acts a strong hint for duplicate content, you can now use the HTML link elements rel=”next” and rel=”prev” to indicate the relationship between component URLs in a paginated series. Throughout the web, a paginated series of content may take many shapes—it can be an article divided into several component pages, or a product category with items spread across several pages, or a forum thread divided into a sequence of URLs. <a href="http://googlewebmastercentral.blogspot.ro/2011/09/pagination-with-relnext-and-relprev.html">Read more</a></span></a></td>
           	<td class="left">
				<?php if (isset($seopagination['prevnext'])) { ?>
                <input type="checkbox" name="seopagination[prevnext]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="seopagination[prevnext]" value="1" />
                <?php } ?></td>            
          </tr>	 		  
        </tbody>
       
             
      </table>	  
    </form>
  </div>
</div>

<?php echo $footer; ?>