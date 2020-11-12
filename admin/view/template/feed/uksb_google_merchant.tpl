<?php echo $header; ?>
<style type="text/css">
table.form > tbody > tr > td:first-child {
    width: 400px;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($error_duplicate) { ?>
  <div class="warning"><?php echo $error_duplicate; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/feed.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general_settings; ?></a><a href="#tab-google-settings"><?php echo $tab_google_settings; ?></a><a href="#tab-google-feeds"><?php echo $tab_google_feeds; ?></a><a href="#tab-bing-feeds"><?php echo $tab_bing_feeds; ?></a><a href="#tab-ciao-feeds"><?php echo $tab_ciao_feeds; ?></a><a href="#tab-thefind-feeds"><?php echo $tab_thefind_feeds; ?></a><a href="#tab-pricegrabber-yahoo-feeds"><?php echo $tab_pricegrabber_yahoo_feeds; ?></a><a href="#tab-nextag-feeds"><?php echo $tab_nextag_feeds; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="uksb_google_merchant_status">
                <?php if ($uksb_google_merchant_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_characters; ?><br /><span class="help"><?php echo $help_characters; ?></span></td>
            <td><select name="uksb_google_merchant_characters">
                <?php if ($uksb_google_merchant_characters) { ?>
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
            <td><select name="uksb_google_merchant_split" id="split">
                <?php if ($uksb_google_merchant_split=='100') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100" selected="selected">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='250') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250" selected="selected">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='500') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500" selected="selected">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='1000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000" selected="selected">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='1500') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500" selected="selected">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='2000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000" selected="selected">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='3000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000" selected="selected">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='5000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000" selected="selected">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='10000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000" selected="selected">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='20000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000" selected="selected">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='30000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000" selected="selected">30000</option>
                <option value="50000">50000</option>
                <?php } elseif ($uksb_google_merchant_split=='50000') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000" selected="selected">50000</option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="30000">30000</option>
                <option value="50000">50000</option>
                <?php } ?>
              </select><span id="split_help" style="display:none; color:red;"><br /><?php echo $help_split_help; ?></span></td>
          </tr>
          <tr>
            <td><?php echo $entry_fullpath; ?><br /><span class="help"><?php echo $help_fullpath; ?></span></td>
            <td><select name="uksb_google_merchant_fullpath" id="fullpath">
                <?php if ($uksb_google_merchant_fullpath=='full') { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="full" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="full"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_info; ?></td>
            <td><?php echo $help_info; ?></td>
          </tr>
        </table>
        </div>



        <div id="tab-google-settings">
        <table class="form">
          <tr>
            <td valign="top"><?php echo $entry_google_category; ?><br /><span class="help"><?php echo $help_google_category; ?><br /><br /><?php echo $entry_choose_google_category_xml; ?></span></td>
            <td><img src="view/image/flags/gb.png" /> <input type="text" name="uksb_google_merchant_google_category_gb" value="<?php echo $uksb_google_merchant_google_category_gb; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=en_GB#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <select name="uksb_google_merchant_tax_gb">
                <?php if ($uksb_google_merchant_tax_gb) { ?>
                <option value="1" selected="selected"><?php echo $text_price_inc_tax; ?></option>
                <option value="0"><?php echo $text_price_exc_tax; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_price_inc_tax; ?></option>
                <option value="0" selected="selected"><?php echo $text_price_exc_tax; ?></option>
                <?php } ?>
              </select><br /><br />
            <img src="view/image/flags/us.png" /> <input type="text" name="uksb_google_merchant_google_category_us" value="<?php echo $uksb_google_merchant_google_category_us; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=en_US#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <?php echo $text_price_exc_tax; ?><br /><br />
            <img src="view/image/flags/au.png" /> <input type="text" name="uksb_google_merchant_google_category_au" value="<?php echo $uksb_google_merchant_google_category_au; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=en_AU#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <select name="uksb_google_merchant_tax_au">
                <?php if ($uksb_google_merchant_tax_au) { ?>
                <option value="1" selected="selected"><?php echo $text_price_inc_tax; ?></option>
                <option value="0"><?php echo $text_price_exc_tax; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_price_inc_tax; ?></option>
                <option value="0" selected="selected"><?php echo $text_price_exc_tax; ?></option>
                <?php } ?>
              </select><br /><br />
            <img src="view/image/flags/fr.png" /> <input type="text" name="uksb_google_merchant_google_category_fr" value="<?php echo $uksb_google_merchant_google_category_fr; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=fr_FR#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <select name="uksb_google_merchant_tax_fr">
                <?php if ($uksb_google_merchant_tax_fr) { ?>
                <option value="1" selected="selected"><?php echo $text_price_inc_tax; ?></option>
                <option value="0"><?php echo $text_price_exc_tax; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_price_inc_tax; ?></option>
                <option value="0" selected="selected"><?php echo $text_price_exc_tax; ?></option>
                <?php } ?>
              </select><br /><br />
            <img src="view/image/flags/de.png" /> <input type="text" name="uksb_google_merchant_google_category_de" value="<?php echo $uksb_google_merchant_google_category_de; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=de_DE#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <select name="uksb_google_merchant_tax_de">
                <?php if ($uksb_google_merchant_tax_de) { ?>
                <option value="1" selected="selected"><?php echo $text_price_inc_tax; ?></option>
                <option value="0"><?php echo $text_price_exc_tax; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_price_inc_tax; ?></option>
                <option value="0" selected="selected"><?php echo $text_price_exc_tax; ?></option>
                <?php } ?>
              </select><br /><br />
            <img src="view/image/flags/it.png" /> <input type="text" name="uksb_google_merchant_google_category_it" value="<?php echo $uksb_google_merchant_google_category_it; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=it_IT#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <select name="uksb_google_merchant_tax_it">
                <?php if ($uksb_google_merchant_tax_it) { ?>
                <option value="1" selected="selected"><?php echo $text_price_inc_tax; ?></option>
                <option value="0"><?php echo $text_price_exc_tax; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_price_inc_tax; ?></option>
                <option value="0" selected="selected"><?php echo $text_price_exc_tax; ?></option>
                <?php } ?>
              </select><br /><br />
            <img src="view/image/flags/nl.png" /> <input type="text" name="uksb_google_merchant_google_category_nl" value="<?php echo $uksb_google_merchant_google_category_nl; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=nl_NL#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <select name="uksb_google_merchant_tax_nl">
                <?php if ($uksb_google_merchant_tax_nl) { ?>
                <option value="1" selected="selected"><?php echo $text_price_inc_tax; ?></option>
                <option value="0"><?php echo $text_price_exc_tax; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_price_inc_tax; ?></option>
                <option value="0" selected="selected"><?php echo $text_price_exc_tax; ?></option>
                <?php } ?>
              </select><br /><br />
            <img src="view/image/flags/es.png" /> <input type="text" name="uksb_google_merchant_google_category_es" value="<?php echo $uksb_google_merchant_google_category_es; ?>" style="width:400px; font-size:9px;" /> <a onclick="window.open('http://www.google.com/support/merchants/bin/answer.py?answer=160081&hl=es_ES#sel_xml','google');"><img src="view/image/add.png" border="0" alt="" title="<?php echo $entry_choose_google_category; ?>"></a> <select name="uksb_google_merchant_tax_es">
                <?php if ($uksb_google_merchant_tax_es) { ?>
                <option value="1" selected="selected"><?php echo $text_price_inc_tax; ?></option>
                <option value="0"><?php echo $text_price_exc_tax; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_price_inc_tax; ?></option>
                <option value="0" selected="selected"><?php echo $text_price_exc_tax; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
              <td><?php echo $entry_history_days; ?><br /><span class="help"><?php echo $help_history_days; ?></span></td>
              <td>
                  <input type="text" name="uksb_google_merchant_history_days" value="<?php echo $uksb_google_merchant_history_days; ?>"/>
              </td>
          </tr>
          <tr>
            <td><?php echo $entry_condition; ?><br /><span class="help"><?php echo $help_condition; ?></span></td>
            <td><select name="uksb_google_merchant_condition">
                <?php if ($uksb_google_merchant_condition=='refurbished') { ?>
                <option value="new"><?php echo $text_condition_new; ?></option>
                <option value="used"><?php echo $text_condition_used; ?></option>
                <option value="refurbished" selected="selected"><?php echo $text_condition_ref; ?></option>
                <?php } elseif ($uksb_google_merchant_condition=='used') { ?>
                <option value="new"><?php echo $text_condition_new; ?></option>
                <option value="used" selected="selected"><?php echo $text_condition_used; ?></option>
                <option value="refurbished"><?php echo $text_condition_ref; ?></option>
                <?php } else { ?>
                <option value="new" selected="selected"><?php echo $text_condition_new; ?></option>
                <option value="used"><?php echo $text_condition_used; ?></option>
                <option value="refurbished"><?php echo $text_condition_ref; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_mpn; ?><br /><span class="help"><?php echo $help_mpn; ?></span></td>
            <td><select name="uksb_google_merchant_mpn">
                <?php if ($uksb_google_merchant_mpn=='mpn') { ?>
                <option value="sku"><?php echo $text_sku; ?></option>
                <option value="model"><?php echo $text_model; ?></option>
                <option value="mpn" selected="selected"><?php echo $text_mpn; ?></option>
                <option value="location"><?php echo $text_location; ?></option>
                <?php } elseif ($uksb_google_merchant_mpn=='location') { ?>
                <option value="sku"><?php echo $text_sku; ?></option>
                <option value="model"><?php echo $text_model; ?></option>
                <option value="mpn"><?php echo $text_mpn; ?></option>
                <option value="location" selected="selected"><?php echo $text_location; ?></option>
                <?php } elseif ($uksb_google_merchant_mpn=='sku') { ?>
                <option value="sku" selected="selected"><?php echo $text_sku; ?></option>
                <option value="model"><?php echo $text_model; ?></option>
                <option value="mpn"><?php echo $text_mpn; ?></option>
                <option value="location"><?php echo $text_location; ?></option>
                <?php } else { ?>
                <option value="sku"><?php echo $text_sku; ?></option>
                <option value="model" selected="selected"><?php echo $text_model; ?></option>
                <option value="mpn"><?php echo $text_mpn; ?></option>
                <option value="location"><?php echo $text_location; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_gtin; ?><br /><span class="help"><?php echo $help_gtin; ?></span></td>
            <td><select name="uksb_google_merchant_gtin">
                <?php if ($uksb_google_merchant_gtin=='location') { ?>
                <option value="upc"><?php echo $text_upc; ?></option>
                <option value="sku"><?php echo $text_sku; ?></option>
                <option value="gtin"><?php echo $text_gtin; ?></option>
                <option value="location" selected="selected"><?php echo $text_location; ?></option>
                <?php } elseif ($uksb_google_merchant_gtin=='gtin') { ?>
                <option value="upc"><?php echo $text_upc; ?></option>
                <option value="sku"><?php echo $text_sku; ?></option>
                <option value="gtin" selected="selected"><?php echo $text_gtin; ?></option>
                <option value="location"><?php echo $text_location; ?></option>
                <?php } elseif ($uksb_google_merchant_gtin=='sku') { ?>
                <option value="upc"><?php echo $text_upc; ?></option>
                <option value="sku" selected="selected"><?php echo $text_sku; ?></option>
                <option value="gtin"><?php echo $text_gtin; ?></option>
                <option value="location"><?php echo $text_location; ?></option>
                <?php } else { ?>
                <option value="upc" selected="selected"><?php echo $text_upc; ?></option>
                <option value="sku"><?php echo $text_sku; ?></option>
                <option value="gtin"><?php echo $text_gtin; ?></option>
                <option value="location"><?php echo $text_location; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_required_attributes; ?><br /><span class="help"><?php echo $help_required_attributes; ?></span></td>
            <td><label for="uksb_google_merchant_required_mpn"><?php echo $text_req_mpn; ?></label><input type="checkbox" name="uksb_google_merchant_required_mpn" value="1"<?php if ($uksb_google_merchant_required_mpn==1) { ?> checked="checked"<?php } ?> /><br />
            <label for="uksb_google_merchant_required_gtin"><?php echo $text_req_gtin; ?></label><input type="checkbox" name="uksb_google_merchant_required_gtin" value="1"<?php if ($uksb_google_merchant_required_gtin==1) { ?> checked="checked"<?php } ?> /><br />
            <label for="uksb_google_merchant_required_brand"><?php echo $text_req_brand; ?></label><input type="checkbox" name="uksb_google_merchant_required_brand" value="1"<?php if ($uksb_google_merchant_required_brand==1) { ?> checked="checked"<?php } ?> /></td>
          </tr>
          <tr>
            <td><?php echo $entry_info; ?></td>
            <td><?php echo $help_info; ?></td>
          </tr>
        </table>
        </div>





        <div id="tab-google-feeds">
        <table class="form">
          <tr>
            <td><?php echo $entry_site; ?><br /><span class="help"><?php echo $help_site; ?></span></td>
            <td><select name="uksb_google_merchant_site" id="google_site">
                <option value="default" selected="selected">Store Default</option>
                <option value="gb">United Kingdom</option>
                <option value="us">United States of America</option>
                <option value="au">Australia</option>
                <option value="fr">France</option>
                <option value="de">Deutschland</option>
                <option value="it">Italia</option>
                <option value="nl">Nederlands</option>
                <option value="es">Espana</option>
              </select></td>
          </tr>
          <?php
          $feeds = explode("^", $data_feed);
          $i=0;
          foreach($feeds as $feed){
          ?>
<!--          <tr>
            <td><?php echo $entry_data_feed; ?></td>
            <td><textarea id="feed_url_<?php echo $i; ?>" cols="40" rows="5"><?php echo $feed; ?></textarea></td>
          </tr>-->
          <?php
          $i++;
          } ?>
          <?php
          $xml_url = explode("^", $xml_url);
          $i=0;
          foreach($xml_url as $key=>$xml){
          ?>
          <tr>
            <td><?php echo $entry_xml_file.' (Last Modified - '.$xml_file_time[$key].' ) '; ?></td>
            <td><?php echo $xml; ?></td>
          </tr>
          <?php
          $i++;
          } ?>
          <tr>
            <td><?php echo $entry_info; ?></td>
            <td><?php echo $help_info; ?></td>
          </tr>
        </table>
        </div>



        <div id="tab-bing-feeds">
        <table class="form">
          <?php
          $bingfeeds = explode("^", $data_bingfeed);
          $i=0;
          foreach($bingfeeds as $bingfeed){
          ?><tr>
            <td><?php echo $entry_data_feed; ?></td>
            <td><textarea id="bingfeed_url_<?php echo $i; ?>" cols="40" rows="5"><?php echo $bingfeed; ?></textarea></td>
          </tr>
          <?php
          $i++;
          } ?>
          <tr>
            <td><?php echo $entry_info; ?></td>
            <td><?php echo $help_info; ?></td>
          </tr>
        </table>
        </div>


        <div id="tab-ciao-feeds">
        <table class="form">
          <tr>
            <td><h2>In Development</h2><p>This feature is currently under development, but will be available soon!</p></td>
          </tr>
        </table>
        </div>



        <div id="tab-thefind-feeds">
        <table class="form">
          <tr>
            <td><h2>In Development</h2><p>This feature is currently under development, but will be available soon!</p></td>
          </tr>
        </table>
        </div>




       <div id="tab-pricegrabber-yahoo-feeds">
        <table class="form">
          <tr>
            <td><h2>In Development</h2><p>This feature is currently under development, but will be available soon!</p></td>
          </tr>
        </table>
        </div>



        <div id="tab-nextag-feeds">
        <table class="form">
          <tr>
            <td><h2>In Development</h2><p>This feature is currently under development, but will be available soon!</p></td>
          </tr>
        </table>
        </div>

      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>
<script type="text/javascript"><!--
$(document).ready(function(){ 
<?php $i = 0; reset($feeds);
	foreach($feeds as $feed){
		if(($this->config->get('config_language')=='en'||$this->config->get('config_language')=='de'||$this->config->get('config_language')=='fr'||$this->config->get('config_language')=='it'||$this->config->get('config_language')=='nl'||$this->config->get('config_language')=='es')&&($this->config->get('config_currency')=='GBP'||$this->config->get('config_currency')=='USD'||$this->config->get('config_currency')=='AUD'||$this->config->get('config_currency')=='EUR')){ ?>
		$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=<?php echo $this->config->get('config_language'); ?>&currency=<?php echo $this->config->get('config_currency'); ?>');
	<?php }else{ ?>
		$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=en&currency=GBP');
	<?php } $i++; } ?>
			
	$("#split").change(function(){
		$("#split_help").css("display", "inline");
	});

	$("#google_site").change(function(){
		var site;
		site = $("#google_site").val();
		
		if(site == 'default'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=<?php echo $this->config->get('config_language'); ?>&currency=<?php echo $this->config->get('config_currency'); ?>');
			<?php $i++; } ?>
		}else if(site == 'gb'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=en&currency=GBP');
			<?php $i++; } ?>
		}else if(site == 'us'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=en&currency=USD');
			<?php $i++; } ?>
		}else if(site == 'au'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=en&currency=AUD');
			<?php $i++; } ?>
		}else if(site == 'fr'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=fr&currency=EUR');
			<?php $i++; } ?>
		}else if(site == 'de'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=de&currency=EUR');
			<?php $i++; } ?>
		}else if(site == 'it'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=it&currency=EUR');
			<?php $i++; } ?>
		}else if(site == 'nl'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=nl&currency=EUR');
			<?php $i++; } ?>
		}else if(site == 'es'){
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=es&currency=EUR');
			<?php $i++; } ?>
		}else{
			<?php $i = 0; reset($feeds);
			foreach($feeds as $feed){
				if(($this->config->get('config_language')=='en'||$this->config->get('config_language')=='de'||$this->config->get('config_language')=='fr'||$this->config->get('config_language')=='it'||$this->config->get('config_language')=='nl'||$this->config->get('config_language')=='es')&&($this->config->get('config_currency')=='GBP'||$this->config->get('config_currency')=='USD'||$this->config->get('config_currency')=='AUD'||$this->config->get('config_currency')=='EUR')){ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=<?php echo $this->config->get('config_language'); ?>&currency=<?php echo $this->config->get('config_currency'); ?>');
			<?php }else{ ?>
				$("textarea#feed_url_<?php echo $i; ?>").text('<?php echo $feed; ?>&language=en&currency=GBP');
			<?php } $i++; } ?>
		}
	});
});
//--></script>