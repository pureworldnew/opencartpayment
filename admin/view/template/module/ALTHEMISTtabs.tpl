<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><i class="icon-save"></i> <?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><i class="icon-undo"></i> <?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="vtabs">
        <?php $module_row = 1; ?>
        <?php foreach ($modules as $module) { ?>
        <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo $tab_module . ' ' . $module_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
        <?php $module_row++; ?>
        <?php } ?>
        <span id="module-add"><?php echo $button_add_module; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span> </div>
      <?php $module_row = 1; ?>
      <?php foreach ($modules as $module) { ?>
      <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
      
      <div id="language-<?php echo $module_row; ?>" class="htabs htabs1-<?php echo $module_row; ?>">

<?php foreach ($languages as $language) { ?>
                  <a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
<?php } ?>
</div>

<?php foreach ($languages as $language) { ?>

<div id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" class="tabs-content">
<table class="form">

          <tr>
            <td>Module title:</td>
<td><input type="text" name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][module_title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['module_title'][$language['language_id']]) ? $module['module_title'][$language['language_id']] : ''; ?>" size="40" /></td>
</tr>
</table>
</div>
<?php } ?>
      
        <table class="form">
          <tr>
          <tr>
            <td class="left">Module type</td>
            <td class="left">
            <select name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][tabsmode]" class="spicy">
                    <?php if ($module['tabsmode'] != '') {
              					$selected_mode = 'selected="selected"';
              					?>
                    <option value="ALTHEMISTtabs_tabs.php" <?php if($module['tabsmode']=='ALTHEMISTtabs_tabs.php'){echo $selected_mode;} ?>>Tabs</option>
                    <option value="ALTHEMISTtabs_accordeon.php" <?php if($module['tabsmode']=='ALTHEMISTtabs_accordeon.php'){echo $selected_mode;} ?>>Accordion</option>
                    <option value="ALTHEMISTtabs_toggle.php" <?php if($module['tabsmode']=='ALTHEMISTtabs_toggle.php'){echo $selected_mode;} ?>>Toggle</option>
                    <?php } else { ?>
                    <option value="ALTHEMISTtabs_tabs.php" selected="selected">Tabs</option>
                    <option value="ALTHEMISTtabs_accordeon.php">Accordion</option>
                    <option value="ALTHEMISTtabs_toggle.php">Toggle</option>
                    <?php } ?>
                  </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_layout; ?></td>
            <td><select name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][layout_id]" class="spicy">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_position; ?></td>
            <td><select name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][position]" class="spicy">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected">Content top</option>
                <?php } else { ?>
                <option value="content_top">Content top</option>
                <?php } ?>
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected">Content bottom</option>
                <?php } else { ?>
                <option value="content_bottom">Content bottom</option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][status]" class="spicy">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" class="spicy" /></td>
          </tr>
        </table>
        <table id="section_<?php echo $module_row; ?>" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_title_description; ?></td>
              <td class="left"></td>
            </tr>
          </thead>
          <?php $section_row = 0; ?>
          <?php foreach($module['sections'] as $section){ ?>
          <tbody id="section-row-<?php echo $module_row; ?>-<?php echo $section_row; ?>" class="section">
            <tr>
              
              <td class="left"><div class="choose_icon">
                  <p>Choose icon:</p>
                  <h3 class="icon_preview"><i class="<?php echo $section['icon']; ?>"></i></h3>
                  
                  <select name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][sections][<?php echo $section_row; ?>][icon]" class="spicy">
                    <?php if ($section['icon'] != '') {
              					$selected_icon = 'selected="selected"';
              					?>
                    <option value="icon-anchor" <?php if($section['icon']=='icon-anchor'){echo $selected_icon;} ?>>Anchor</option>
                    <option value="icon-archive" <?php if($section['icon']=='icon-archive'){echo $selected_icon;} ?>>Archive</option>
                    <option value="icon-arrows" <?php if($section['icon']=='icon-arrows-alt'){echo $selected_icon;} ?>>Arrows</option>
                    <option value="icon-asterisk" <?php if($section['icon']=='icon-asterisk'){echo $selected_icon;} ?>>Asterisk</option>
                    <option value="icon-bell" <?php if($section['icon']=='icon-bell'){echo $selected_icon;} ?>>Bell</option>
                    <option value="icon-bolt" <?php if($section['icon']=='icon-bolt'){echo $selected_icon;} ?>>Bolt</option>
                    <option value="icon-bar-chart" <?php if($section['icon']=='icon-bolt'){echo $selected_icon;} ?>>Bar chart</option>
                    <option value="icon-bars" <?php if($section['icon']=='icon-bars'){echo $selected_icon;} ?>>Bars</option>
                    <option value="icon-beer" <?php if($section['icon']=='icon-beer'){echo $selected_icon;} ?>>Beer</option>
                    <option value="icon-book" <?php if($section['icon']=='icon-book'){echo $selected_icon;} ?>>Book</option>
                    <option value="icon-bookmark" <?php if($section['icon']=='icon-bookmark'){echo $selected_icon;} ?>>Bookmark</option>
                    <option value="icon-briefcase" <?php if($section['icon']=='icon-briefcase'){echo $selected_icon;} ?>>Briefcase</option>
                    <option value="icon-bug" <?php if($section['icon']=='icon-bug'){echo $selected_icon;} ?>>Bug</option>
                    <option value="icon-building" <?php if($section['icon']=='icon-building'){echo $selected_icon;} ?>>Building</option>
                    <option value="icon-bullhorn" <?php if($section['icon']=='icon-bullhorn'){echo $selected_icon;} ?>>Megaphone</option>
                    <option value="icon-calendar" <?php if($section['icon']=='icon-calendar'){echo $selected_icon;} ?>>Calendar</option>
                    <option value="icon-camera" <?php if($section['icon']=='icon-camera'){echo $selected_icon;} ?>>Camera</option>
                    <option value="icon-certificate" <?php if($section['icon']=='icon-certificate'){echo $selected_icon;} ?>>Certificate</option>
                    <option value="icon-check-circle" <?php if($section['icon']=='icon-check-circle'){echo $selected_icon;} ?>>Checkmark</option>
                    <option value="icon-cloud" <?php if($section['icon']=='icon-cloud'){echo $selected_icon;} ?>>Cloud</option>
                    <option value="icon-cloud-download" <?php if($section['icon']=='icon-cloud-download'){echo $selected_icon;} ?>>Cloud download</option>
                    <option value="icon-cloud-upload" <?php if($section['icon']=='icon-cloud-upload'){echo $selected_icon;} ?>>Cloud Upload</option>
                    <option value="icon-code" <?php if($section['icon']=='icon-code'){echo $selected_icon;} ?>>Code</option>
                    <option value="icon-coffee" <?php if($section['icon']=='icon-coffee'){echo $selected_icon;} ?>>Coffee</option>
                    <option value="icon-time" <?php if($section['icon']=='icon-time'){echo $selected_icon;} ?>>Clock</option>
                    <option value="icon-cog" <?php if($section['icon']=='icon-cog'){echo $selected_icon;} ?>>Cog</option>
                    <option value="icon-cogs" <?php if($section['icon']=='icon-cogs'){echo $selected_icon;} ?>>Cogs</option>
                    <option value="icon-comment" <?php if($section['icon']=='icon-comment'){echo $selected_icon;} ?>>Comment</option>
                    <option value="icon-comments" <?php if($section['icon']=='icon-comments'){echo $selected_icon;} ?>>Comments</option>
                    <option value="icon-compass" <?php if($section['icon']=='icon-compass'){echo $selected_icon;} ?>>Compass</option>
                    <option value="icon-credit-card" <?php if($section['icon']=='icon-credit-card'){echo $selected_icon;} ?>>Credit card</option>
                    <option value="icon-crop" <?php if($section['icon']=='icon-crop'){echo $selected_icon;} ?>>Crop</option>
                    <option value="icon-crosshairs" <?php if($section['icon']=='icon-crosshairs'){echo $selected_icon;} ?>>Crosshairs</option>
                    <option value="icon-cutlery" <?php if($section['icon']=='icon-cutlery'){echo $selected_icon;} ?>>Cutlery</option>
                    <option value="icon-dashboard" <?php if($section['icon']=='icon-dashboard'){echo $selected_icon;} ?>>Dashboard</option>
                    <option value="icon-desctop" <?php if($section['icon']=='icon-desctop'){echo $selected_icon;} ?>>Desctop</option>
                    <option value="icon-download" <?php if($section['icon']=='icon-download'){echo $selected_icon;} ?>>Download</option>
                    <option value="icon-edit" <?php if($section['icon']=='icon-edit'){echo $selected_icon;} ?>>Edit</option>
                    <option value="icon-envelope" <?php if($section['icon']=='icon-envelope'){echo $selected_icon;} ?>>Envelope</option>
                    <option value="icon-eraser" <?php if($section['icon']=='icon-eraser'){echo $selected_icon;} ?>>Eraser</option>
                    <option value="icon-eye" <?php if($section['icon']=='icon-eye'){echo $selected_icon;} ?>>Eye</option>
                    <option value="icon-eye-slash" <?php if($section['icon']=='icon-eye-slash'){echo $selected_icon;} ?>>Eye slash</option>
                    <option value="icon-female" <?php if($section['icon']=='icon-female'){echo $selected_icon;} ?>>Female</option>
                    <option value="icon-fighter-jet" <?php if($section['icon']=='icon-fighter-jet'){echo $selected_icon;} ?>>Fighter jet</option>
                    <option value="icon-film" <?php if($section['icon']=='icon-film'){echo $selected_icon;} ?>>Film</option>
                    <option value="icon-fire" <?php if($section['icon']=='icon-fire'){echo $selected_icon;} ?>>Fire</option>
                    <option value="icon-fire-extinguisher" <?php if($section['icon']=='icon-fire-extinguisher'){echo $selected_icon;} ?>>Fire extinguisher</option>
                    <option value="icon-flag" <?php if($section['icon']=='icon-flag'){echo $selected_icon;} ?>>Flag</option>
                    <option value="icon-flask" <?php if($section['icon']=='icon-flask'){echo $selected_icon;} ?>>Flask</option>
                    <option value="icon-folder-open" <?php if($section['icon']=='icon-folder-open'){echo $selected_icon;} ?>>Folder</option>
                    <option value="icon-gamepad" <?php if($section['icon']=='icon-gamepad'){echo $selected_icon;} ?>>Gamepad</option>
                    <option value="icon-gavel" <?php if($section['icon']=='icon-gavel'){echo $selected_icon;} ?>>Gavel</option>
                    <option value="icon-gift" <?php if($section['icon']=='icon-gift'){echo $selected_icon;} ?>>Gift</option>
                    <option value="icon-glass" <?php if($section['icon']=='icon-glass'){echo $selected_icon;} ?>>Glass</option>
                    <option value="icon-globe" <?php if($section['icon']=='icon-globe'){echo $selected_icon;} ?>>Globe</option>
                    <option value="icon-headphones" <?php if($section['icon']=='icon-headphones'){echo $selected_icon;} ?>>Headphones</option>
                    <option value="icon-heart" <?php if($section['icon']=='icon-heart'){echo $selected_icon;} ?>>Heart</option>
                    <option value="icon-home" <?php if($section['icon']=='icon-home'){echo $selected_icon;} ?>>Home</option>
                    <option value="icon-inbox" <?php if($section['icon']=='icon-inbox'){echo $selected_icon;} ?>>Inbox</option>
                    <option value="icon-key" <?php if($section['icon']=='icon-key'){echo $selected_icon;} ?>>Key</option>
                    <option value="icon-keyboard" <?php if($section['icon']=='icon-keyboard'){echo $selected_icon;} ?>>Keyboard</option>
                    <option value="icon-laptop" <?php if($section['icon']=='icon-laptop'){echo $selected_icon;} ?>>Laptop</option>
                    <option value="icon-leaf" <?php if($section['icon']=='icon-leaf'){echo $selected_icon;} ?>>Leaf</option>
                    <option value="icon-lightbulb" <?php if($section['icon']=='icon-lightbulb'){echo $selected_icon;} ?>>Lightbulb</option>
                    <option value="icon-location-arrow" <?php if($section['icon']=='icon-location-arrow'){echo $selected_icon;} ?>>Location arrow</option>
                    <option value="icon-lock" <?php if($section['icon']=='icon-lock'){echo $selected_icon;} ?>>Lock</option>
                    <option value="icon-magic" <?php if($section['icon']=='icon-magic'){echo $selected_icon;} ?>>Magic</option>
                    <option value="icon-magnet" <?php if($section['icon']=='icon-magnet'){echo $selected_icon;} ?>>Magnet</option>
                    <option value="icon-male" <?php if($section['icon']=='icon-male'){echo $selected_icon;} ?>>Male</option>
                    <option value="icon-map-marker" <?php if($section['icon']=='icon-map-marker'){echo $selected_icon;} ?>>Map marker</option>
                    <option value="icon-microphone" <?php if($section['icon']=='icon-microphone'){echo $selected_icon;} ?>>Microphone</option>
                    <option value="icon-mobile" <?php if($section['icon']=='icon-mobile'){echo $selected_icon;} ?>>Mobile phone</option>
                    <option value="icon-money" <?php if($section['icon']=='icon-money'){echo $selected_icon;} ?>>Money</option>
                    <option value="icon-moon" <?php if($section['icon']=='icon-moon'){echo $selected_icon;} ?>>Moon</option>
                    <option value="icon-music" <?php if($section['icon']=='icon-music'){echo $selected_icon;} ?>>Music</option>
                    <option value="icon-pencil" <?php if($section['icon']=='icon-pencil'){echo $selected_icon;} ?>>Pencil</option>
                    <option value="icon-phone" <?php if($section['icon']=='icon-phone'){echo $selected_icon;} ?>>Phone</option>
                    <option value="icon-picture" <?php if($section['icon']=='icon-picture'){echo $selected_icon;} ?>>Picture</option>
                    <option value="icon-plane" <?php if($section['icon']=='icon-plane'){echo $selected_icon;} ?>>Plane</option>
                    <option value="icon-plus" <?php if($section['icon']=='icon-plus'){echo $selected_icon;} ?>>Plus</option>
                    <option value="icon-power-off" <?php if($section['icon']=='icon-power-off'){echo $selected_icon;} ?>>Power off</option>
                    <option value="icon-print" <?php if($section['icon']=='icon-print'){echo $selected_icon;} ?>>Print</option>
                    <option value="icon-puzzle-piece" <?php if($section['icon']=='icon-puzzle-piece'){echo $selected_icon;} ?>>Puzzle</option>
                    <option value="icon-lightbulb" <?php if($section['icon']=='icon-lightbulb'){echo $selected_icon;} ?>>Lightbulb</option>
                    <option value="icon-qrcode" <?php if($section['icon']=='icon-qrcode'){echo $selected_icon;} ?>>QR Code</option>
                    <option value="icon-question" <?php if($section['icon']=='icon-question'){echo $selected_icon;} ?>>Question</option>
                    <option value="icon-random" <?php if($section['icon']=='icon-random'){echo $selected_icon;} ?>>Random</option>
                    <option value="icon-refresh" <?php if($section['icon']=='icon-refresh'){echo $selected_icon;} ?>>Refresh</option>
                    <option value="icon-road" <?php if($section['icon']=='icon-road'){echo $selected_icon;} ?>>Road</option>
                    <option value="icon-rocket" <?php if($section['icon']=='icon-rocket'){echo $selected_icon;} ?>>Rocket</option>
                    <option value="icon-search" <?php if($section['icon']=='icon-search'){echo $selected_icon;} ?>>Search</option>
                    <option value="icon-share" <?php if($section['icon']=='icon-share'){echo $selected_icon;} ?>>Share</option>
                    <option value="icon-shield" <?php if($section['icon']=='icon-shield'){echo $selected_icon;} ?>>Shield</option>
                    <option value="icon-shopping-cart" <?php if($section['icon']=='icon-shopping-cart'){echo $selected_icon;} ?>>Shopping cart</option>
                    <option value="icon-signal" <?php if($section['icon']=='icon-signal'){echo $selected_icon;} ?>>Signal</option>
                    <option value="icon-sitemap" <?php if($section['icon']=='icon-sitemap'){echo $selected_icon;} ?>>Sitemap</option>
                    <option value="icon-spinner" <?php if($section['icon']=='icon-spinner'){echo $selected_icon;} ?>>Spinner</option>
                    <option value="icon-star" <?php if($section['icon']=='icon-star'){echo $selected_icon;} ?>>Star</option>
                    <option value="icon-star-half" <?php if($section['icon']=='icon-star-half'){echo $selected_icon;} ?>>Star half</option>
                    <option value="icon-suitcase" <?php if($section['icon']=='icon-suitcase'){echo $selected_icon;} ?>>Suitcase</option>
                    <option value="icon-sun" <?php if($section['icon']=='icon-sun'){echo $selected_icon;} ?>>Sun</option>
                    <option value="icon-tablet" <?php if($section['icon']=='icon-tablet'){echo $selected_icon;} ?>>Tablet</option>
                    <option value="icon-tag" <?php if($section['icon']=='icon-tag'){echo $selected_icon;} ?>>Tag</option>
                    <option value="icon-tag" <?php if($section['icon']=='icon-tag'){echo $selected_icon;} ?>>Tags</option>
                    <option value="icon-tasks" <?php if($section['icon']=='icon-tasks'){echo $selected_icon;} ?>>Tasks</option>
                    <option value="icon-thumb-tack" <?php if($section['icon']=='icon-thumb-tack'){echo $selected_icon;} ?>>Thumb tack (pin)</option>
                    <option value="icon-thumbs-down" <?php if($section['icon']=='icon-thumbs-down'){echo $selected_icon;} ?>>Thumbs down</option>
                    <option value="icon-thumbs-up" <?php if($section['icon']=='icon-thumbs-up'){echo $selected_icon;} ?>>Thumbs up</option>
                    <option value="icon-ticket" <?php if($section['icon']=='icon-ticket'){echo $selected_icon;} ?>>Ticket</option>
                    <option value="icon-tint" <?php if($section['icon']=='icon-tint'){echo $selected_icon;} ?>>Tint (drop)</option>
                    <option value="icon-trash" <?php if($section['icon']=='icon-trash'){echo $selected_icon;} ?>>Trash</option>
                    <option value="icon-trophy" <?php if($section['icon']=='icon-trophy'){echo $selected_icon;} ?>>Trophy</option>
                    <option value="icon-truck" <?php if($section['icon']=='icon-truck'){echo $selected_icon;} ?>>Truck</option>
                    <option value="icon-umbrella" <?php if($section['icon']=='icon-umbrella'){echo $selected_icon;} ?>>Umbrella</option>
                    <option value="icon-unlock" <?php if($section['icon']=='icon-unlock'){echo $selected_icon;} ?>>Unlock</option>
                    <option value="icon-upload" <?php if($section['icon']=='icon-upload'){echo $selected_icon;} ?>>Upload</option>
                    <option value="icon-user" <?php if($section['icon']=='icon-user'){echo $selected_icon;} ?>>User</option>
                    <option value="icon-users" <?php if($section['icon']=='icon-users'){echo $selected_icon;} ?>>Users</option>
                    <option value="icon-volume-up" <?php if($section['icon']=='icon-volume-up'){echo $selected_icon;} ?>>Volume</option>
                    <option value="icon-wrench" <?php if($section['icon']=='icon-wrench'){echo $selected_icon;} ?>>Wrench</option>
                    <option value="icon-link" <?php if($section['icon']=='icon-link'){echo $selected_icon;} ?>>Link</option>
                    <option value="icon-scissors" <?php if($section['icon']=='icon-scissors'){echo $selected_icon;} ?>>Cut (scissors)</option>
                    <option value="icon-paperclip" <?php if($section['icon']=='icon-paperclip'){echo $selected_icon;} ?>>Paperclip</option>
                    <option value="icon-arrows-alt" <?php if($section['icon']=='icon-arrows-alt'){echo $selected_icon;} ?>>Arrows alt</option>
                    <option value="icon-android" <?php if($section['icon']=='icon-android'){echo $selected_icon;} ?>>Android</option>
                    <option value="icon-apple" <?php if($section['icon']=='icon-apple'){echo $selected_icon;} ?>>Apple</option>
                    <option value="icon-css3" <?php if($section['icon']=='icon-css3'){echo $selected_icon;} ?>>CSS3</option>
                    <option value="icon-dribbble" <?php if($section['icon']=='icon-dribbble'){echo $selected_icon;} ?>>Dribbble</option>
                    <option value="icon-dropbox" <?php if($section['icon']=='icon-dropbox'){echo $selected_icon;} ?>>Dropbox</option>
                    <option value="icon-facebook" <?php if($section['icon']=='icon-facebook'){echo $selected_icon;} ?>>Facebook</option>
                    <option value="icon-flickr" <?php if($section['icon']=='icon-flickr'){echo $selected_icon;} ?>>Flickr</option>
                    <option value="icon-foursquare" <?php if($section['icon']=='icon-foursquare'){echo $selected_icon;} ?>>Foursquare</option>
                    <option value="icon-github" <?php if($section['icon']=='icon-github'){echo $selected_icon;} ?>>Github</option>
                    <option value="icon-google-plus" <?php if($section['icon']=='icon-google-plus'){echo $selected_icon;} ?>>Google plus</option>
                    <option value="icon-html5" <?php if($section['icon']=='icon-html5'){echo $selected_icon;} ?>>HTML5</option>
                    <option value="icon-instagram" <?php if($section['icon']=='icon-instagram'){echo $selected_icon;} ?>>Instagram</option>
                    <option value="icon-linkedin" <?php if($section['icon']=='icon-linkedin'){echo $selected_icon;} ?>>Linkedin</option>
                    <option value="icon-linux" <?php if($section['icon']=='icon-linux'){echo $selected_icon;} ?>>Linux</option>
                    <option value="icon-pinterest" <?php if($section['icon']=='icon-pinterest'){echo $selected_icon;} ?>>Pinterest</option>
                    <option value="icon-skype" <?php if($section['icon']=='icon-skype'){echo $selected_icon;} ?>>Skype</option>
                    <option value="icon-tumblr" <?php if($section['icon']=='icon-tumblr'){echo $selected_icon;} ?>>Tumblr</option>
                    <option value="icon-twitter" <?php if($section['icon']=='icon-twitter'){echo $selected_icon;} ?>>Twitter</option>
                    <option value="icon-ambulance" <?php if($section['icon']=='icon-ambulance'){echo $selected_icon;} ?>>Ambulance</option>
                    <option value="icon-hospital" <?php if($section['icon']=='icon-hospital'){echo $selected_icon;} ?>>Hospital</option>
                    <option value="icon-medkit" <?php if($section['icon']=='icon-medkit'){echo $selected_icon;} ?>>Medkit</option>
                    <option value="icon-user-md" <?php if($section['icon']=='icon-user-md'){echo $selected_icon;} ?>>Doctor (MD)</option>
                    <?php } else { ?>
                    <option value="icon-anchor" selected="selected">Anchor</option>
                    <option value="icon-archive">Archive</option>
                    <option value="icon-arrows">Arrows</option>
                    <option value="icon-asterisk">Asterisk</option>
                    <option value="icon-bell">Bell</option>
                    <option value="icon-bolt">Bolt</option>
                    <option value="icon-bar-chart">Bar chart</option>
                    <option value="icon-bars">Bars</option>
                    <option value="icon-beer">Beer</option>
                    <option value="icon-book">Book</option>
                    <option value="icon-bookmark">Bookmark</option>
                    <option value="icon-briefcase">Briefcase</option>
                    <option value="icon-bug">Bug</option>
                    <option value="icon-building">Building</option>
                    <option value="icon-bullhorn">Megaphone</option>
                    <option value="icon-calendar">Calendar</option>
                    <option value="icon-camera">Camera</option>
                    <option value="icon-certificate">Certificate</option>
                    <option value="icon-check-circle">Checkmark</option>
                    <option value="icon-cloud">Cloud</option>
                    <option value="icon-cloud-download">Cloud download</option>
                    <option value="icon-cloud-upload">Cloud Upload</option>
                    <option value="icon-code">Code</option>
                    <option value="icon-coffee">Coffee</option>
                    <option value="icon-time">Clock</option>
                    <option value="icon-cog">Cog</option>
                    <option value="icon-cogs">Cogs</option>
                    <option value="icon-comment">Comment</option>
                    <option value="icon-comments">Comments</option>
                    <option value="icon-compass">Compass</option>
                    <option value="icon-credit-card">Credit card</option>
                    <option value="icon-crop">Crop</option>
                    <option value="icon-crosshairs">Crosshairs</option>
                    <option value="icon-cutlery">Cutlery</option>
                    <option value="icon-dashboard">Dashboard</option>
                    <option value="icon-desctop">Desctop</option>
                    <option value="icon-download">Download</option>
                    <option value="icon-edit">Edit</option>
                    <option value="icon-envelope">Envelope</option>
                    <option value="icon-eraser">Eraser</option>
                    <option value="icon-eye">Eye</option>
                    <option value="icon-eye-slash">Eye slash</option>
                    <option value="icon-female">Female</option>
                    <option value="icon-fighter-jet">Fighter jet</option>
                    <option value="icon-film">Film</option>
                    <option value="icon-fire">Fire</option>
                    <option value="icon-fire-extinguisher">Fire extinguisher</option>
                    <option value="icon-flag">Flag</option>
                    <option value="icon-flask">Flask</option>
                    <option value="icon-folder-open">Folder</option>
                    <option value="icon-gamepad">Gamepad</option>
                    <option value="icon-gavel">Gavel</option>
                    <option value="icon-gift">Gift</option>
                    <option value="icon-glass">Glass</option>
                    <option value="icon-globe">Globe</option>
                    <option value="icon-headphones">Headphones</option>
                    <option value="icon-heart">Heart</option>
                    <option value="icon-home">Home</option>
                    <option value="icon-inbox">Inbox</option>
                    <option value="icon-key">Key</option>
                    <option value="icon-keyboard">Keyboard</option>
                    <option value="icon-laptop">Laptop</option>
                    <option value="icon-leaf">Leaf</option>
                    <option value="icon-lightbulb">Lightbulb</option>
                    <option value="icon-location-arrow">Location arrow</option>
                    <option value="icon-lock">Lock</option>
                    <option value="icon-magic">Magic</option>
                    <option value="icon-magnet">Magnet</option>
                    <option value="icon-male">Male</option>
                    <option value="icon-map-marker">Map marker</option>
                    <option value="icon-microphone">Microphone</option>
                    <option value="icon-mobile">Mobile phone</option>
                    <option value="icon-money">Money</option>
                    <option value="icon-moon">Moon</option>
                    <option value="icon-music">Music</option>
                    <option value="icon-pencil">Pencil</option>
                    <option value="icon-phone">Phone</option>
                    <option value="icon-picture">Picture</option>
                    <option value="icon-plane">Plane</option>
                    <option value="icon-plus">Plus</option>
                    <option value="icon-power-off">Power off</option>
                    <option value="icon-print">Print</option>
                    <option value="icon-puzzle-piece">Puzzle</option>
                    <option value="icon-lightbulb">Lightbulb</option>
                    <option value="icon-qrcode">QR Code</option>
                    <option value="icon-question">Question</option>
                    <option value="icon-random">Random</option>
                    <option value="icon-refresh">Refresh</option>
                    <option value="icon-road">Road</option>
                    <option value="icon-rocket">Rocket</option>
                    <option value="icon-search">Search</option>
                    <option value="icon-share">Share</option>
                    <option value="icon-shield">Shield</option>
                    <option value="icon-shopping-cart">Shopping cart</option>
                    <option value="icon-signal">Signal</option>
                    <option value="icon-sitemap">Sitemap</option>
                    <option value="icon-spinner">Spinner</option>
                    <option value="icon-star">Star</option>
                    <option value="icon-star-half">Star half</option>
                    <option value="icon-suitcase">Suitcase</option>
                    <option value="icon-sun">Sun</option>
                    <option value="icon-tablet">Tablet</option>
                    <option value="icon-tag">Tag</option>
                    <option value="icon-tag">Tags</option>
                    <option value="icon-tasks">Tasks</option>
                    <option value="icon-thumb-tack">Thumb tack (pin)</option>
                    <option value="icon-thumbs-down">Thumbs down</option>
                    <option value="icon-thumbs-up">Thumbs up</option>
                    <option value="icon-ticket">Ticket</option>
                    <option value="icon-tint">Tint (drop)</option>
                    <option value="icon-trash">Trash</option>
                    <option value="icon-trophy">Trophy</option>
                    <option value="icon-truck">Truck</option>
                    <option value="icon-umbrella">Umbrella</option>
                    <option value="icon-unlock">Unlock</option>
                    <option value="icon-upload">Upload</option>
                    <option value="icon-user">User</option>
                    <option value="icon-user">Users</option>
                    <option value="icon-volume-up">Volume</option>
                    <option value="icon-wrench">Wrench</option>
                    <option value="icon-link">Link</option>
                    <option value="icon-scissors">Cut (scissors)</option>
                    <option value="icon-paperclip">Paperclip</option>
                    <option value="icon-arrows-alt">Arrows alt</option>
                    <option value="icon-android">Android</option>
                    <option value="icon-apple">Apple</option>
                    <option value="icon-css3">CSS3</option>
                    <option value="icon-dribbble">Dribbble</option>
                    <option value="icon-dropbox">Dropbox</option>
                    <option value="icon-facebook">Facebook</option>
                    <option value="icon-flickr">Flickr</option>
                    <option value="icon-foursquare">Foursquare</option>
                    <option value="icon-github">Github</option>
                    <option value="icon-google-plus">Google plus</option>
                    <option value="icon-html5">HTML5</option>
                    <option value="icon-instagram">Instagram</option>
                    <option value="icon-linkedin">Linkedin</option>
                    <option value="icon-linux">Linux</option>
                    <option value="icon-pinterest">Pinterest</option>
                    <option value="icon-skype">Skype</option>
                    <option value="icon-tumblr">Tumblr</option>
                    <option value="icon-twitter">Twitter</option>
                    <option value="icon-ambulance">Ambulance</option>
                    <option value="icon-hospital">Hospital</option>
                    <option value="icon-medkit">Medkit</option>
                    <option value="icon-user-md">Doctor (MD)</option>
                    <?php } ?>
                  </select>
                  <div class="clear"></div>
                </div>
                <div id="language-<?php echo $module_row; ?>-<?php echo $section_row; ?>" class="htabs">
                  <?php foreach ($languages as $language) { ?>
                  <a href="#tab-language-<?php echo $module_row; ?>-<?php echo $section_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                  <?php } ?>
                </div>
                <?php foreach ($languages as $language) { ?>
                <div id="tab-language-<?php echo $module_row; ?>-<?php echo $section_row; ?>-<?php echo $language['language_id']; ?>"> <?php echo $text_title; ?>
                  <input name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][sections][<?php echo $section_row; ?>][title][<?php echo $language['language_id']; ?>]" id="title-<?php echo $module_row; ?>-<?php echo $section_row; ?>-<?php echo $language['language_id']; ?>" value="<?php echo isset($module['sections'][$section_row]['title'][$language['language_id']]) ? $module['sections'][$section_row]['title'][$language['language_id']] : ''; ?>" size="50" />
                  <br />
                  <br />
                  <textarea name="ALTHEMISTtabs_module[<?php echo $module_row; ?>][sections][<?php echo $section_row; ?>][description][<?php echo $language['language_id']; ?>]" id="description-<?php echo $module_row; ?>-<?php echo $section_row; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($module['sections'][$section_row]['description'][$language['language_id']]) ? $module['sections'][$section_row]['description'][$language['language_id']] : ''; ?></textarea>
                </div>
                <?php } ?></td>
              <td><a class="button" onclick="removeSection(<?php echo $module_row; ?>, <?php echo $section_row; ?>);"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $section_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td><a class="button" onclick="addSection(<?php echo $module_row; ?>);"><?php echo $button_add_section; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <?php $module_row++; ?>
      <?php } ?>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
<?php $section_row = 0; ?>
<?php   foreach($module['sections'] as $section) { ?>
<?php      foreach ($languages as $language) { ?>
				CKEDITOR.replace('description-<?php echo $module_row; ?>-<?php echo $section_row;?>-<?php echo $language['language_id']; ?>', {
					filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
				});
				
				$('#language-<?php echo $module_row; ?>-<?php echo $section_row; ?> a').tabs();
<?php      } ?>
<?php   $section_row++; ?>
<?php   } ?>
<?php $module_row++; ?>
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;
var section_row;

function addModule() {	
	section_row = 0;
	html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';
	html += '    <div id="language-' + module_row + '" class="htabs htabs1-' + module_row + '">';
    <?php foreach ($languages as $language) { ?>
	html += '      <a href="#tab-language-' + module_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
    <?php } ?>
	html += '      </div>';
    <?php foreach ($languages as $language) { ?>
	html += '    <div id="tab-language-' + module_row + '-<?php echo $language['language_id']; ?>" class="tabs-content">';
    html += '    <table class="form">';
    html += '    <tr>';
    html += '    <td>Module title:</td>';
    html += '    <td><input type="text" name="ALTHEMISTtabs_module[' + module_row + '][module_title][<?php echo $language['language_id']; ?>]" size="40" /></td>';
    html += '    </tr>';
    html += '    </table>';
	html += '      </div>';
    <?php } ?>
	html += '  <table class="form">';

	html += '    <td>Module type</td>';
	html += '    <td>';
	html += '    <select name="ALTHEMISTtabs_module[' + module_row + '][tabsmode]" class="spicy">';
	html += '    <option value="ALTHEMISTtabs_tabs.php">Tabs</option>';
	html += '    <option value="ALTHEMISTtabs_accordeon.php">Accordion</option>';
	html += '    <option value="ALTHEMISTtabs_toggle.php">Toggle</option>';
	html += '    </select>';
	html += '    </td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_layout; ?></td>';
	html += '      <td><select name="ALTHEMISTtabs_module[' + module_row + '][layout_id]" class="spicy">';
	<?php foreach ($layouts as $layout) { ?>
	html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_position; ?></td>';
	html += '      <td><select name="ALTHEMISTtabs_module[' + module_row + '][position]" class="spicy">';
	html += '        <option value="content_top">Content top</option>';
	html += '        <option value="content_bottom">Content bottom</option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_status; ?></td>';
	html += '      <td><select name="ALTHEMISTtabs_module[' + module_row + '][status]" class="spicy">';
	html += '        <option value="1"><?php echo $text_enabled; ?></option>';
	html += '        <option value="0"><?php echo $text_disabled; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_sort_order; ?></td>';
	html += '      <td><input type="text" name="ALTHEMISTtabs_module[' + module_row + '][sort_order]" value="" size="3" class="spicy" /></td>';
	html += '    </tr>';
	html += '  </table>'; 
	
    html += '  <table id="section_' + module_row + '" class="list">';
	html += '	 <thead>';
	html += '	    <tr>';
	html += '	       <td class="left"><?php echo $entry_image; ?></td>';
	html += '	       <td class="left"><?php echo $entry_title_description; ?></td>';
	html += '	       <td class="left"></td>';
	html += '	    </tr>';
	html += '    </thead>';
	html += '    <tfoot>';
    html += '     <tr>';
	html += '         <td colspan="2"></td>';
    html += '         <td class="left"><a onclick="addSection(' + module_row + ');" class="button"><?php echo $button_add_section; ?></a></td>';
    html += '     </tr>';
    html += '    </tfoot>';
    html += '  </table>';
	html += '</div>';
	
	$('#form').append(html);
	
	$('#language-' + module_row + ' a').tabs();
	
	$('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $tab_module; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
	
	$('.vtabs a').tabs();
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}
//--></script> 
<script type="text/javascript"><!--
function addSection( module_number) {
	section_row = $('#tab-module-' + module_number + ' .section').length;
	html  = '<tbody id="section-row-' + module_number + '-' + section_row + '" class="section">';
	html += '  <tr>';
	html += '    <td class="left">';
	html += '    <div class="choose_icon">';
	html += '    <p>Choose icon:</p>';
	html += '    <h3 class="icon_preview"><i class="icon-anchor"></i></h3>';
	html += '    <select name="ALTHEMISTtabs_module[' + module_number + '][sections][' + section_row + '][icon]" class="spicy">';
	html += '    <option value="icon-anchor">Anchor</option>';
	html += '    <option value="icon-archive">Archive</option>';
	html += '    <option value="icon-arrows">Arrows</option>';
	html += '    <option value="icon-aterisk">Asterisk</option>';
	html += '    <option value="icon-bell">Bell</option>';
	html += '    <option value="icon-bolt">Bolt</option>';
	html += '    <option value="icon-bar-chart">Bar chart</option>';
	html += '    <option value="icon-bars">Bars</option>';
		html += '    <option value="icon-beer">Beer</option>';
	html += '    <option value="icon-book">Book</option>';
	html += '    <option value="icon-bookmark">Bookmark</option>';
	html += '    <option value="icon-briefcase">Briefcase</option>';
	html += '    <option value="icon-bug">Bug</option>';
	html += '    <option value="icon-building">Building</option>';
	html += '    <option value="icon-bullhorn">Megaphone</option>';
	html += '    <option value="icon-calendar">Calendar</option>';
	html += '    <option value="icon-camera">Camera</option>';
	html += '    <option value="icon-certificate">Certificate</option>';
	html += '    <option value="icon-check-circle">Checkmark</option>';
	html += '    <option value="icon-cloud">Cloud</option>';
	html += '    <option value="icon-cloud-download">Cloud download</option>';
	html += '    <option value="icon-cloud-upload">Cloud Upload</option>';
	html += '    <option value="icon-code">Code</option>';
	html += '    <option value="icon-coffee">Coffee</option>';
	html += '    <option value="icon-time">Clock</option>';
	html += '    <option value="icon-cog">Cog</option>';
	html += '    <option value="icon-cogs">Cogs</option>';
	html += '    <option value="icon-comment">Comment</option>';
	html += '    <option value="icon-comments">Comments</option>';
	html += '    <option value="icon-compass">Compass</option>';
	html += '    <option value="icon-credit-card">Credit card</option>';
	html += '    <option value="icon-crop">Crop</option>';
	html += '    <option value="icon-crosshairs">Crosshairs</option>';
	html += '    <option value="icon-cutlery">Cutlery</option>';
	html += '    <option value="icon-dashboard">Dashboard</option>';
	html += '    <option value="icon-desctop">Desctop</option>';
	html += '    <option value="icon-download">Download</option>';
	html += '    <option value="icon-edit">Edit</option>';
	html += '    <option value="icon-envelope">Envelope</option>';
	html += '    <option value="icon-eraser">Eraser</option>';
	html += '    <option value="icon-eye">Eye</option>';
	html += '    <option value="icon-eye-slash">Eye slash</option>';
	html += '    <option value="icon-female">Female</option>';
	html += '    <option value="icon-fighter-jet">Fighter jet</option>';
	html += '    <option value="icon-film">Film</option>';
	html += '    <option value="icon-fire">Fire</option>';
	html += '    <option value="icon-fire-extinguisher">Fire extinguisher</option>';
	html += '    <option value="icon-flag">Flag</option>';
	html += '    <option value="icon-flask">Flask</option>';
	html += '    <option value="icon-folder-open">Folder</option>';
	html += '    <option value="icon-gamepad">Gamepad</option>';
	html += '    <option value="icon-gavel">Gavel</option>';
	html += '    <option value="icon-gift">Gift</option>';
	html += '    <option value="icon-glass">Glass</option>';
	html += '    <option value="icon-globe">Globe</option>';
	html += '    <option value="icon-headphones">Headphones</option>';
	html += '    <option value="icon-heart">Heart</option>';
	html += '    <option value="icon-home">Home</option>';
	html += '    <option value="icon-inbox">Inbox</option>';
	html += '    <option value="icon-key">Key</option>';
	html += '    <option value="icon-keyboard">Keyboard</option>';
	html += '    <option value="icon-laptop">Laptop</option>';
	html += '    <option value="icon-leaf">Leaf</option>';
	html += '    <option value="icon-lightbulb">Lightbulb</option>';
	html += '    <option value="icon-location-arrow">Location arrow</option>';
	html += '    <option value="icon-lock">Lock</option>';
	html += '    <option value="icon-magic">Magic</option>';
	html += '    <option value="icon-magnet">Magnet</option>';
	html += '    <option value="icon-male">Male</option>';
	html += '    <option value="icon-map-marker">Map marker</option>';
	html += '    <option value="icon-microphone">Microphone</option>';
	html += '    <option value="icon-mobile">Mobile phone</option>';
	html += '    <option value="icon-money">Money</option>';
	html += '    <option value="icon-moon">Moon</option>';
	html += '    <option value="icon-music">Music</option>';
	html += '    <option value="icon-pencil">Pencil</option>';
	html += '    <option value="icon-phone">Phone</option>';
	html += '    <option value="icon-picture">Picture</option>';
	html += '    <option value="icon-plane">Plane</option>';
	html += '    <option value="icon-plus">Plus</option>';
	html += '    <option value="icon-power-off">Power off</option>';
	html += '    <option value="icon-print">Print</option>';
	html += '    <option value="icon-puzzle-piece">Puzzle</option>';
	html += '    <option value="icon-lightbulb">Lightbulb</option>';
	html += '    <option value="icon-qrcode">QR Code</option>';
	html += '    <option value="icon-question">Question</option>';
	html += '    <option value="icon-random">Random</option>';
	html += '    <option value="icon-refresh">Refresh</option>';
	html += '    <option value="icon-road">Road</option>';
	html += '    <option value="icon-rocket">Rocket</option>';
	html += '    <option value="icon-search">Search</option>';
	html += '    <option value="icon-share">Share</option>';
	html += '    <option value="icon-shield">Shield</option>';
	html += '    <option value="icon-shopping-cart">Shopping cart</option>';
	html += '    <option value="icon-signal">Signal</option>';
	html += '    <option value="icon-sitemap">Sitemap</option>';
	html += '    <option value="icon-spinner">Spinner</option>';
	html += '    <option value="icon-star">Star</option>';
	html += '    <option value="icon-star-half">Star half</option>';
	html += '    <option value="icon-suitcase">Suitcase</option>';
	html += '    <option value="icon-sun">Sun</option>';
	html += '    <option value="icon-tablet">Tablet</option>';
	html += '    <option value="icon-tag">Tag</option>';
	html += '    <option value="icon-tag">Tags</option>';
	html += '    <option value="icon-tasks">Tasks</option>';
	html += '    <option value="icon-thumb-tack">Thumb tack (pin)</option>';
	html += '    <option value="icon-thumbs-down">Thumbs down</option>';
	html += '    <option value="icon-thumbs-up">Thumbs up</option>';
	html += '    <option value="icon-ticket">Ticket</option>';
	html += '    <option value="icon-tint">Tint (drop)</option>';
	html += '    <option value="icon-trash">Trash</option>';
	html += '    <option value="icon-trophy">Trophy</option>';
	html += '    <option value="icon-truck">Truck</option>';
	html += '    <option value="icon-umbrella">Umbrella</option>';
	html += '    <option value="icon-unlock">Unlock</option>';
	html += '    <option value="icon-upload">Upload</option>';
	html += '    <option value="icon-user">User</option>';
	html += '    <option value="icon-user">Users</option>';
	html += '    <option value="icon-volume-up">Volume</option>';
	html += '    <option value="icon-wrench">Wrench</option>';
	html += '    <option value="icon-link">Link</option>';
	html += '    <option value="icon-scissors">Cut (scissors)</option>';
	html += '    <option value="icon-paperclip">Paperclip</option>';
	html += '    <option value="icon-arrows-alt">Arrows alt</option>';
	html += '    <option value="icon-android">Android</option>';
	html += '    <option value="icon-apple">Apple</option>';
	html += '    <option value="icon-css3">CSS3</option>';
	html += '    <option value="icon-dribbble">Dribbble</option>';
	html += '    <option value="icon-dropbox">Dropbox</option>';
	html += '    <option value="icon-facebook">Facebook</option>';
	html += '    <option value="icon-flickr">Flickr</option>';
	html += '    <option value="icon-foursquare">Foursquare</option>';
	html += '    <option value="icon-github">Github</option>';
	html += '    <option value="icon-google-plus">Google plus</option>';
	html += '    <option value="icon-html5">HTML5</option>';
	html += '    <option value="icon-instagram">Instagram</option>';
	html += '    <option value="icon-linkedin">Linkedin</option>';
	html += '    <option value="icon-linux">Linux</option>';
	html += '    <option value="icon-pinterest">Pinterest</option>';
	html += '    <option value="icon-skype">Skype</option>';
	html += '    <option value="icon-tumblr">Tumblr</option>';
	html += '    <option value="icon-twitter">Twitter</option>';
	html += '    <option value="icon-ambulance">Ambulance</option>';
	html += '    <option value="icon-hospital">Hospital</option>';
	html += '    <option value="icon-medkit">Medkit</option>';
	html += '    <option value="icon-user-md">Doctor (MD)</option>';	
	html += '    </select>';
	html += '    </div>';
	html += '  		<div id="language-' + module_row + '-' + section_row + '" class="htabs">';
					<?php foreach ($languages as $language) { ?>
    html += '    		<a href="#tab-language-'+ module_number  + '-' + section_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
					<?php } ?>
	html += '       </div>';
     
					<?php foreach ($languages as $language) { ?>
	html += '    	<div id="tab-language-'+ module_number + '-' + section_row + '-<?php echo $language['language_id']; ?>">';
	html += '          	<?php echo $text_title; ?><input name="ALTHEMISTtabs_module[' + module_number + '][sections][' + section_row + '][title][<?php echo $language['language_id']; ?>]" id="title-' + module_number + '-' + section_row +'-<?php echo $language['language_id']; ?>" size="60" /><br /><br />';
	html += '          	<textarea name="ALTHEMISTtabs_module[' + module_number + '][sections][' + section_row + '][description][<?php echo $language['language_id']; ?>]" id="description-' + module_number + '-' + section_row + '-<?php echo $language['language_id']; ?>"></textarea>';
	html += '    	</div>';
					<?php } ?>
	html += '    </td>';
	html += '    <td><a class="button" onclick="removeSection('+ module_number +',' + section_row +');"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#section_' + module_number + ' tfoot').before(html);
	
	$('#language-' + module_row + '-' + section_row + ' a').tabs();
	
	
	<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('description-' + module_number + '-' + section_row + '-<?php echo $language['language_id']; ?>', {
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});  

	<?php } ?>
	
	section_row++;
}

function removeSection(module_number, section_number){
	$('#section-row-' + module_number + '-' + section_number).remove();
}

function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};

//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
$('.htabs1-0 a').tabs();
$('.htabs1-1 a').tabs();
$('.htabs1-2 a').tabs();
$('.htabs1-3 a').tabs();
$('.htabs1-4 a').tabs();
$('.htabs1-5 a').tabs();
$('.htabs1-6 a').tabs();
$('.htabs1-7 a').tabs();
$('.htabs1-8 a').tabs();
$('.htabs1-9 a').tabs();
$('.htabs1-10 a').tabs();
$('.htabs1-11 a').tabs();
$('.htabs1-12 a').tabs();
$('.htabs1-13 a').tabs();
$('.htabs1-14 a').tabs();
$('.htabs1-15 a').tabs();
$('.htabs1-16 a').tabs();
$('.htabs1-17 a').tabs();
$('.htabs1-18 a').tabs();
$('.htabs1-19 a').tabs();
$('.htabs1-20 a').tabs();
$('.htabs1-21 a').tabs();
$('.htabs1-22 a').tabs();
$('.htabs1-23 a').tabs();
$('.htabs1-24 a').tabs();
$('.htabs1-25 a').tabs();
$('.htabs1-26 a').tabs();
$('.htabs1-27 a').tabs();
$('.htabs1-28 a').tabs();
$('.htabs1-29 a').tabs();
$('.htabs1-30 a').tabs();
$('#language-1-0 a').tabs();
$('#language-1-1 a').tabs();
$('#language-1-2 a').tabs();
$('#language-1-3 a').tabs();
$('#language-1-4 a').tabs();
$('#language-1-5 a').tabs();
$('#language-1-6 a').tabs();
$('#language-1-7 a').tabs();
$('#language-1-8 a').tabs();
$('#language-1-9 a').tabs();
$('#language-1-10 a').tabs();
$('#language-2-0 a').tabs();
$('#language-2-1 a').tabs();
$('#language-2-2 a').tabs();
$('#language-2-3 a').tabs();
$('#language-2-4 a').tabs();
$('#language-2-5 a').tabs();
$('#language-2-6 a').tabs();
$('#language-2-7 a').tabs();
$('#language-2-8 a').tabs();
$('#language-2-9 a').tabs();
$('#language-2-10 a').tabs();
$('#language-3-0 a').tabs();
$('#language-3-1 a').tabs();
$('#language-3-2 a').tabs();
$('#language-3-3 a').tabs();
$('#language-3-4 a').tabs();
$('#language-3-5 a').tabs();
$('#language-3-6 a').tabs();
$('#language-3-7 a').tabs();
$('#language-3-8 a').tabs();
$('#language-3-9 a').tabs();
$('#language-3-10 a').tabs();
$('#language-4-0 a').tabs();
$('#language-4-1 a').tabs();
$('#language-4-2 a').tabs();
$('#language-4-3 a').tabs();
$('#language-4-4 a').tabs();
$('#language-4-5 a').tabs();
$('#language-4-6 a').tabs();
$('#language-4-7 a').tabs();
$('#language-4-8 a').tabs();
$('#language-4-9 a').tabs();
$('#language-4-10 a').tabs();
$('#language-5-0 a').tabs();
$('#language-5-1 a').tabs();
$('#language-5-2 a').tabs();
$('#language-5-3 a').tabs();
$('#language-5-4 a').tabs();
$('#language-5-5 a').tabs();
$('#language-5-6 a').tabs();
$('#language-5-7 a').tabs();
$('#language-5-8 a').tabs();
$('#language-5-9 a').tabs();
$('#language-5-10 a').tabs();
$('#language-6-0 a').tabs();
$('#language-6-1 a').tabs();
$('#language-6-2 a').tabs();
$('#language-6-3 a').tabs();
$('#language-6-4 a').tabs();
$('#language-6-5 a').tabs();
$('#language-6-6 a').tabs();
$('#language-6-7 a').tabs();
$('#language-6-8 a').tabs();
$('#language-6-9 a').tabs();
$('#language-6-10 a').tabs();
$('#language-7-0 a').tabs();
$('#language-7-1 a').tabs();
$('#language-7-2 a').tabs();
$('#language-7-3 a').tabs();
$('#language-7-4 a').tabs();
$('#language-7-5 a').tabs();
$('#language-7-6 a').tabs();
$('#language-7-7 a').tabs();
$('#language-7-8 a').tabs();
$('#language-7-9 a').tabs();
$('#language-7-10 a').tabs();
$('#language-8-0 a').tabs();
$('#language-8-1 a').tabs();
$('#language-8-2 a').tabs();
$('#language-8-3 a').tabs();
$('#language-8-4 a').tabs();
$('#language-8-5 a').tabs();
$('#language-8-6 a').tabs();
$('#language-8-7 a').tabs();
$('#language-8-8 a').tabs();
$('#language-8-9 a').tabs();
$('#language-8-10 a').tabs();
//--></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?> 
//--></script> 
 
<?php echo $footer; ?>