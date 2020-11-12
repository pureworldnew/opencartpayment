<?php  if ($menu) { ?>
<div id="advancedm">
  <?php $i=1; ?>
  <div class="box">
    <div class="filter_box">
      <?php if (!empty($values_selected)) {?>
      <?php foreach ($values_selected as $value_sel) {?>
      <?php  $i==1 ? $liclass="first upper" : $liclass="upper";?>
      <dl id="filter_p<?php echo $i; ?>" class="filters opened selected" >
        <dt class="<?php echo $liclass; ?>"><span><em>&nbsp;</em><?php echo $value_sel['dnd']; ?></span><?php echo html_entity_decode($value_sel['tip_code'], ENT_QUOTES, 'UTF-8'); ?></dt>
        <dd class="page_preload"><?php echo $value_sel['html']; ?></dd>
      </dl>
      <?php $i++; } ?>
      <?php } ?>
      <?php if (!empty($values_no_selected)) { 
      ksort($values_no_selected); ?>
      <?php foreach ($values_no_selected as $value_no_select) { ?>
      <?php foreach ($value_no_select as $value_no_sel) { ?>
      <?php  $i==1 ? $liclass="first upper" : $liclass="upper";?>
      <dl id="filter_p<?php echo $i; ?>" class="filters <?php echo $value_no_sel['initval']; ?>">
        <dt class="<?php echo $liclass; ?>"><span><em>&nbsp;</em><?php echo $value_no_sel['name']; ?></span><?php echo html_entity_decode($value_no_sel['tip_code'], ENT_QUOTES, 'UTF-8'); ?></dt>
       <dd class="page_preload"><?php echo $value_no_sel['html']; ?></dd>
      </dl>
      <?php $i++; } ?>
      <?php } ?>
      <?php } ?>
      <dl class="filters">
        <dt class="last"><span>&nbsp;</span></dt>
      </dl>
    </div>
  </div>
</div>
<style type="text/css">
  .button-style{
    position: absolute;
    right: 0px;
    z-index: 999999;
  }
</style>

        <script type="text/javascript">
          function multiselectable(url,id){
           if($("#li"+id).hasClass('active')){
            $("#li"+id).removeClass('active');
           }else{
            $("#li"+id).addClass('active');
           }
              
            var full_url="";
            $(".smenuss").each(function(i){
            if($(this).parent().hasClass('active')){
              $("#advancedm").attr('data-urls','');
            var temp_url=   $(this).attr('data-url');
            var temp_type = temp_url.split('?');
            var temp_sep = '';
            if(temp_type.length > 1)
                temp_sep = temp_type[1];
            full_url += "&"+ temp_sep.replace("filter_att[", "filter_att[");
            }
            });

            //var already_exist=$("#advancedm").attr('data-urls');
            //$("#advancedm").attr('data-urls','');
            $("#advancedm").attr('data-urls',full_url);
          }
          $("#filter_b").click(function(){
            var urls=$("#advancedm").attr('data-urls');

            location.href=window.location.href+urls;
          });
           //Script
          </script>
<!!!!!! INSERT JAVASCRIPT VQMOD !!!!!!!!!!!>
<?php  } ?>