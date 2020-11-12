<?php
  $number = count($sections);
  $start = 0;
  ?>
<div class="box box-ALTHEMISTaccordion althemist" id="ALTHEMISTaccordion<?php echo $module; ?>">


<?php if ($module_title){ ?>
  <h2 class="heading_title"><span><?php echo $module_title; ?></span></h2>
<?php } ?>
<div class="accordion accordion<?php echo $module; ?>" id="accordion<?php echo $module; ?>">
<?php foreach($sections as $section){ ?>
<?php $start = $start + 1; ?>
  <div class="accord-header" id="accord-header<?php echo $module; ?>-header<?php echo $start; ?>">
  <i class="<?php echo $section['icon']; ?>"></i>
<?php if ($section['title'] != "..."){ ?>
<?php echo $section['title']; ?>
      <?php } ?> 
  </div>
  <div class="accord-content" id="accordion<?php echo $module; ?>div<?php echo $start; ?>">
  <?php if ($section['description'] != "..."){ ?>
      <?php echo $section['description']; ?>
      <?php } ?>
  </div>
  <?php } ?>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
   $(".accordion<?php echo $module; ?> .accord-header").click(function() {
     // For active header definition
     $('.accord-header').removeClass('on');
     $(this).addClass('on');
     
     // Accordion actions
     if($(this).next("div").is(":visible")){
       $(this).next("div").slideUp("slow");
     } else {
       $(".accordion<?php echo $module; ?> .accord-content").slideUp("slow");
       $(this).next("div").slideToggle("slow");
     }
  });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
document.getElementById("accordion<?php echo $module; ?>div1").style.display = 'block';
$("#accord-header<?php echo $module; ?>-header1").addClass('on');
});
</script>
