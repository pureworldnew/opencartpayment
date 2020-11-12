<?php echo $header; ?>
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
      <h1><img src="view/image/feed.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
            <tr>
              <td colspan="2"><h2><?php echo $heading_general_settings; ?></h2></td>
            </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="uksb_sitemaps_status">
                <?php if ($uksb_sitemaps_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_image_sitemap; ?></td>
            <td><select name="uksb_image_sitemap">
                <?php if ($uksb_image_sitemap) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_split; ?><br /><span class="help"><?php echo $help_split; ?></span></td>
            <td valign="top"><select name="uksb_sitemaps_split">
                <?php if ($uksb_sitemaps_split=='500') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500" selected="selected">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='1000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000" selected="selected">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='1500') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500" selected="selected">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='2000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000" selected="selected">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='5000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000" selected="selected">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='10000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000" selected="selected">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='20000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000" selected="selected">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='30000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000" selected="selected">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_sitemaps_split=='50000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000" selected="selected">50000</option>
                <?php } else { ?>

                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fullpath; ?><br /><span class="help"><?php echo $help_fullpath; ?></span></td>
            <td valign="top"><select name="uksb_sitemap_fullpath">
                <?php if ($uksb_sitemap_fullpath=='full') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="full" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="full"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
        <table class="form">
            <tr>
              <td colspan="4"><h2><?php echo $entry_sitemap_content; ?></h2><span class="help"><?php echo $help_content; ?></span></td>
            </tr>
          <tr>
            <td><?php echo $entry_products; ?></td>
            <td><?php echo $entry_in_sitemap; ?> <select name="uksb_sitemap_products_on">
                <?php if ($uksb_sitemap_products_on=='0') { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $entry_frequency; ?> <select name="uksb_sitemap_products_fr">
                <?php if ($uksb_sitemap_products_fr == 'always') { ?>
                <option value="always" selected="selected"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_products_fr == 'hourly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly" selected="selected"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_products_fr == 'daily') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily" selected="selected"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_products_fr == 'monthly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly" selected="selected"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_products_fr == 'yearly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly" selected="selected"><?php echo $text_yearly; ?></option>
                <?php } else  { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly" selected="selected"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $entry_priority; ?> <select name="uksb_sitemap_products_pr">
                <?php if ($uksb_sitemap_products_pr == '0.9') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9" selected="selected">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.8') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8" selected="selected">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.7') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7" selected="selected">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.6') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6" selected="selected">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.5') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5" selected="selected">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.4') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4" selected="selected">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.3') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3" selected="selected">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.2') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2" selected="selected">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.1') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1" selected="selected">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_products_pr == '0.0') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0" selected="selected">0.0</option>
                <?php } else { ?>
                <option value="1.0" selected="selected">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_categories; ?></td>
            <td style="width:230px;"><?php echo $entry_in_sitemap; ?> <select name="uksb_sitemap_categories_on">
                <?php if ($uksb_sitemap_categories_on=='0') { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
            <td style="width:230px;"><?php echo $entry_frequency; ?> <select name="uksb_sitemap_categories_fr">
                <?php if ($uksb_sitemap_categories_fr == 'always') { ?>
                <option value="always" selected="selected"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_categories_fr == 'hourly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly" selected="selected"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_categories_fr == 'daily') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily" selected="selected"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_categories_fr == 'monthly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly" selected="selected"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_categories_fr == 'yearly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly" selected="selected"><?php echo $text_yearly; ?></option>
                <?php } else  { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly" selected="selected"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $entry_priority; ?> <select name="uksb_sitemap_categories_pr">
                <?php if ($uksb_sitemap_categories_pr == '1.0') { ?>
                <option value="1.0" selected="selected">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.8') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8" selected="selected">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.7') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7" selected="selected">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.6') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6" selected="selected">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.5') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5" selected="selected">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.4') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4" selected="selected">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.3') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3" selected="selected">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.2') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2" selected="selected">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.1') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1" selected="selected">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_categories_pr == '0.0') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0" selected="selected">0.0</option>
                <?php } else { ?>
                <option value="1.0">1.0</option>
                <option value="0.9" selected="selected">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_manufacturers; ?></td>
            <td><?php echo $entry_in_sitemap; ?> <select name="uksb_sitemap_manufacturers_on">
                <?php if ($uksb_sitemap_manufacturers_on=='0') { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $entry_frequency; ?> <select name="uksb_sitemap_manufacturers_fr">
                <?php if ($uksb_sitemap_manufacturers_fr == 'always') { ?>
                <option value="always" selected="selected"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_manufacturers_fr == 'hourly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly" selected="selected"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_manufacturers_fr == 'daily') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily" selected="selected"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_manufacturers_fr == 'monthly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly" selected="selected"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_manufacturers_fr == 'yearly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly" selected="selected"><?php echo $text_yearly; ?></option>
                <?php } else  { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly" selected="selected"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $entry_priority; ?> <select name="uksb_sitemap_manufacturers_pr">
                <?php if ($uksb_sitemap_manufacturers_pr == '1.0') { ?>
                <option value="1.0" selected="selected">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.9') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9" selected="selected">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.7') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7" selected="selected">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.6') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6" selected="selected">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.5') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5" selected="selected">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.4') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4" selected="selected">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.3') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3" selected="selected">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.2') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2" selected="selected">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.1') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1" selected="selected">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_manufacturers_pr == '0.0') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0" selected="selected">0.0</option>
                <?php } else { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8" selected="selected">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_pages; ?></td>
            <td><?php echo $entry_in_sitemap; ?> <select name="uksb_sitemap_pages_on">
                <?php if ($uksb_sitemap_pages_on=='0') { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $entry_frequency; ?> <select name="uksb_sitemap_pages_fr">
                <?php if ($uksb_sitemap_pages_fr == 'always') { ?>
                <option value="always" selected="selected"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_pages_fr == 'hourly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly" selected="selected"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_pages_fr == 'daily') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily" selected="selected"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_pages_fr == 'monthly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly" selected="selected"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } elseif ($uksb_sitemap_pages_fr == 'yearly') { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly" selected="selected"><?php echo $text_yearly; ?></option>
                <?php } else  { ?>
                <option value="always"><?php echo $text_always; ?></option>
                <option value="hourly"><?php echo $text_hourly; ?></option>
                <option value="daily"><?php echo $text_daily; ?></option>
                <option value="weekly" selected="selected"><?php echo $text_weekly; ?></option>
                <option value="monthly"><?php echo $text_monthly; ?></option>
                <option value="yearly"><?php echo $text_yearly; ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $entry_priority; ?> <select name="uksb_sitemap_pages_pr">
                <?php if ($uksb_sitemap_pages_pr == '1.0') { ?>
                <option value="1.0" selected="selected">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.9') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9" selected="selected">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.8') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8" selected="selected">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.6') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6" selected="selected">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.5') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5" selected="selected">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.4') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4" selected="selected">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.3') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3" selected="selected">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.2') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2" selected="selected">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.1') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1" selected="selected">0.1</option>
                <option value="0.0">0.0</option>
                <?php } elseif ($uksb_sitemap_pages_pr == '0.0') { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0" selected="selected">0.0</option>
                <?php } else { ?>
                <option value="1.0">1.0</option>
                <option value="0.9">0.9</option>
                <option value="0.8">0.8</option>
                <option value="0.7" selected="selected">0.7</option>
                <option value="0.6">0.6</option>
                <option value="0.5">0.5</option>
                <option value="0.4">0.4</option>
                <option value="0.3">0.3</option>
                <option value="0.2">0.2</option>
                <option value="0.1">0.1</option>
                <option value="0.0">0.0</option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_pages_omit; ?></td>
            <td colspan="2">
             <input name="uksb_pages_omit_a" type="checkbox" value="1"<?php if($uksb_pages_omit_a!=''){ ?> checked="checked"<?php } ?> /> <?php echo $text_pg_home; ?>
             <br /><input name="uksb_pages_omit_b" type="checkbox" value="1"<?php if($uksb_pages_omit_b!=''){ ?> checked="checked"<?php } ?> /> <?php echo $text_pg_specials; ?>
             <?php foreach($informations as $information){  ?>
             <br /><input name="uksb_pages_omit_<?php echo $information['information_id']; ?>" type="checkbox" value="1"<?php if(${'uksb_pages_omit_'.$information['information_id']}!=''){ ?> checked="checked"<?php } ?> /> <?php echo $information['title']; ?>
             <?php } ?>
              </td>
          </tr>
        </table>
        <table class="form">
            <tr>
              <td colspan="2"><h2><?php echo $heading_sitemap_urls; ?></h2></h2><span class="help"><?php echo $help_urls; ?></span></td>
            </tr>
          <?php
          $feeds = explode("^", $data_feed);
          foreach($feeds as $feed){
          ?><tr>
            <td><?php echo $entry_data_feed1; ?></td>
            <td><textarea cols="40" rows="5"><?php echo $feed; ?></textarea></td>
          </tr>
          <?php
          } 
          $feeds2 = explode("^", $data_feed2);
          foreach($feeds2 as $feed2){
          ?><tr>
            <td><?php echo $entry_data_feed2; ?></td>
            <td><textarea cols="40" rows="5"><?php echo $feed2; ?></textarea></td>
          </tr>
          <?php
          } ?>
        </table>
        <table class="form">
          <tr>
            <td><?php echo $entry_info; ?></td>
            <td><?php echo $help_info; ?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>