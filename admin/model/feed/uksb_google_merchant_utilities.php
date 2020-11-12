<?php
require_once('../../config.php');

//licensing check
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
    $ssl = 1;
    $home = 'https://www.secureserverssl.co.uk/opencart-extensions/google-merchant/';
} else {
    $ssl = 0;
    $home = 'http://www.opencart-extensions.co.uk/google-merchant/';
}

if ($ssl) {
    $domain = str_replace("https://", "", HTTPS_SERVER);
} else {
    $domain = str_replace("http://", "", HTTP_SERVER);
}

if (extension_loaded('curl')) {
    $curl = 'y';

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $home . 'licensed.php?domain=' . $domain . '&extension=2500');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $licensed = curl_exec($curl);

    curl_close($curl);
} else {
    $error = 1;
}

if ($licensed != 'full') {
    $error = 1;
}

if (!isset($error) && isset($_GET['run'])) {
    require_once(DIR_SYSTEM . 'startup.php');

    // Registry
    $registry = new Registry();

    // Loader
    $loader = new Loader($registry);
    $registry->set('load', $loader);

    // Config
    $config = new Config();
    $registry->set('config', $config);

    // Database 
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $registry->set('db', $db);
}

if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 1) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `ongoogle` = '1'");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 2) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `ongoogle` = '0'");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 3) {
    $query = $db->query("SELECT `value` from `" . DB_PREFIX . "setting` WHERE `key` = 'uksb_google_merchant_mpn'");

    $query2 = $db->query("UPDATE `" . DB_PREFIX . "product` SET `ongoogle` = '0' WHERE (`" . $query->row['value'] . "` = '' OR `" . $query->row['value'] . "` IS NULL) AND (`vmpn` = '' OR `vmpn` IS NULL)");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 4) {
    $query = $db->query("SELECT `value` from `" . DB_PREFIX . "setting` WHERE `key` = 'uksb_google_merchant_gtin'");

    if ($query->row['value'] == 'default') {
        $query2 = $db->query("UPDATE `" . DB_PREFIX . "product` SET `ongoogle` = '0' WHERE (`upc` = '' OR `upc` IS NULL) AND (`ean` = '' OR `ean` IS NULL) AND (`jan` = '' OR `jan` IS NULL) AND (`isbn` = '' OR `isbn` IS NULL) AND (`vgtin` = '' OR vgtin IS NULL)");
    } else {
        $query2 = $db->query("UPDATE `" . DB_PREFIX . "product` SET `ongoogle` = '0' WHERE (`" . $query->row['value'] . "` = '' OR `" . $query->row['value'] . "` IS NULL) AND (`vgtin` = '' OR `vgtin` IS NULL)");
    }

    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 5) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `ongoogle` = '0' WHERE `manufacturer_id` = '0' AND (`brand` = '' OR `brand` IS NULL)");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 6) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `identifier_exists` = '1'");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 7) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `identifier_exists` = '0'");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 8) {
    $query = $db->query("SELECT `value` from `" . DB_PREFIX . "setting` WHERE `key` = 'uksb_google_merchant_mpn'");

    $query2 = $db->query("UPDATE `" . DB_PREFIX . "product` SET `identifier_exists` = '0' WHERE (`" . $query->row['value'] . "` = '' OR `" . $query->row['value'] . "` IS NULL) AND (`vmpn` = '' OR `vmpn` IS NULL)");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 9) {
    $query = $db->query("SELECT `value` from `" . DB_PREFIX . "setting` WHERE `key` = 'uksb_google_merchant_gtin'");

    if ($query->row['value'] == 'default') {
        $query2 = $db->query("UPDATE `" . DB_PREFIX . "product` SET `identifier_exists` = '0' WHERE (`upc` = '' OR `upc` IS NULL) AND (`ean` = '' OR `ean` IS NULL) AND (`jan` = '' OR `jan` IS NULL) AND (`isbn` = '' OR `isbn` IS NULL) AND (`vgtin` = '' OR vgtin IS NULL)");
    } else {
        $query2 = $db->query("UPDATE `" . DB_PREFIX . "product` SET `identifier_exists` = '0' WHERE (`" . $query->row['value'] . "` = '' OR `" . $query->row['value'] . "` IS NULL) AND (`vgtin` = '' OR `vgtin` IS NULL)");
    }

    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 10) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `identifier_exists` = '0' WHERE `manufacturer_id` = '0' AND (`brand` = '' OR `brand` IS NULL)");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 11) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `google_category_gb` = '', `google_category_us` = '', `google_category_au` = '', `google_category_fr` = '', `google_category_de` = '', `google_category_it` = '', `google_category_nl` = '', `google_category_es` = ''");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 12) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "category` SET `google_category_gb` = '', `google_category_us` = '', `google_category_au` = '', `google_category_fr` = '', `google_category_de` = '', `google_category_it` = '', `google_category_nl` = '', `google_category_es` = ''");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 13) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `gcondition` = 'new'");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 14) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `gcondition` = 'used'");
    $completed = 1;
}
if (!isset($error) && isset($_GET['run']) && $_GET['run'] == 15) {
    $query = $db->query("UPDATE `" . DB_PREFIX . "product` SET `gcondition` = 'refurbished'");
    $completed = 1;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>UKSB Google Merchant Utilities</title>
        <link rel="stylesheet" type="text/css" href="../../view/stylesheet/stylesheet.css" />
        <?php if ($completed) { ?>
            <script>
                setTimeout(function(){
                    self.close();
                },2000);
            </script>
        <?php } ?>
    </head>

    <body>
        <?php if ($completed) { ?>
            <p>Operation Complete</p>
            <p><a href="javascript:self.close();">Close Window</a></p>
        <?php } ?>
    </body>
</html>