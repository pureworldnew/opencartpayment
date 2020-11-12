<?php
  $number = count($sections);
  $start = 0;
  ?>
<div class="box box-ALTHEMISTtabs althemist" id="ALTHEMISTtabs<?php echo $module; ?>">

<?php if ($module_title){ ?>
  <h2 class="heading_title"><span><?php echo $module_title; ?></span></h2>
<?php } ?>
<div id="tabs<?php echo $module; ?>" class="htabs"></div>

<?php foreach($sections as $section){ ?>
<?php $start = $start + 1; ?>
<a href="#tab-<?php echo $start; ?>" class="tabberro">
<i class="<?php echo $section['icon']; ?>"></i>
<?php if ($section['title'] != "..."){ ?>
<?php echo $section['title']; ?>
      <?php } ?> 
</a>
<div id="tab-<?php echo $start; ?>" class="tab-content">  
	  
      <?php if ($section['description'] != "..."){ ?>
      <?php echo $section['description']; ?>
      <?php } ?>
</div>

<?php } ?>
<div class="clear"></div>

</div>
<script type="text/javascript">
$(document).ready(function(){	
	$('.tabberro').prependTo('#tabs<?php echo $module; ?>');
	$('#tabs<?php echo $module; ?> a').tabs();
})
</script>
