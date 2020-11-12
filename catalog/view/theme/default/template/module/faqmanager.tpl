<style type="text/css">
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: inherit;
    font-weight: 500;
    line-height: 1.1;
    color: inherit;
}
</style>
<?php if (!empty($sections)) { ?>
<?php usort($sections, function ($a, $b) { return strcmp($a['sort'], $b['sort']); }); ?>
<?php foreach($sections as $section) { ?>
<div class="panel-group" id="section<?php echo $section['index']; ?>" role="tablist" aria-multiselectable="true">
  <?php if ($section['title']){ ?>
    <h3 style="color:#444;"><?php echo $section['title']; ?></h3>
  <?php } ?>
  <?php //usort($section['groups'], function ($a, $b) { return strcmp($a['sort'], $b['sort']); }); ?>
  <?php foreach($section['groups'] as $key => $group){ ?>
  <div class="panel panel-default" style="border-radius:4px; border:1px solid #ddd; margin-top:5px;">
    <div class="panel-heading" role="tab" style="background-color: #f5f5f5;
    border-color: #ddd; color: #333; padding: 6px 15px; border-top-right-radius: 3px; border-top-left-radius: 3px; border-color: #ddd;">
      <h4 class="panel-title" style="line-height: 10px;">
        <a data-toggle="collapse" data-parent="#section<?php echo $section['index']; ?>" href="#item<?php echo $module; ?>-<?php echo $section['index']; ?>-<?php echo $group['id']; ?>" style="font-weight: 500; font-size:14px; color:inherit;">
          <?php echo $group['title']; ?>
        </a>
      </h4>
    </div>
    <div id="item<?php echo $module; ?>-<?php echo $section['index']; ?>-<?php echo $group['id']; ?>" class="panel-collapse collapse" role="tabpanel">
      <div class="panel-body" style="border-top: 1px solid #ddd; padding: 15px; ">
        <?php echo $group['description']; ?>
      </div>
    </div>
  </div>
  <?php } ?>
 </div>
<?php } ?>
<?php } ?>