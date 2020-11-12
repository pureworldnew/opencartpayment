<?php
  $number = count($sections);
  $start = 0;
  ?>
<div class="box box-ALTHEMISTtoggle althemist" id="ALTHEMISTtoggle<?php echo $module; ?>">


<?php if ($module_title){ ?>
  <h2 class="heading_title"><span><?php echo $module_title; ?></span></h2>
<?php } ?>
<div class="toggling toggling<?php echo $module; ?>" id="toggling<?php echo $module; ?>">
<?php foreach($sections as $section){ ?>
<?php $start = $start + 1; ?>
  <div class="togg-header">
  <i class="<?php echo $section['icon']; ?>"></i>
<?php if ($section['title'] != "..."){ ?>
<?php echo $section['title']; ?>
      <?php } ?> 
  </div>
  <div class="togg-content">
  <?php if ($section['description'] != "..."){ ?>
      <?php echo $section['description']; ?>
      <?php } ?>
  </div>
  <?php } ?>
</div>
<div class="clear"></div>

</div>
<script type="text/javascript">
$(document).ready(function() {
   $(".toggling<?php echo $module; ?> .togg-header").click(function() {
     // For active header definition
     $('.togg-header').removeClass('on');
     $(this).addClass('on');
     
     // Accordion actions
     if($(this).next("div").is(":visible")){
       $(this).next("div").slideUp("slow");
     } else {
       $(this).next("div").slideToggle("slow");
     }
  });
});
</script>
