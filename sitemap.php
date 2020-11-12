<?php
// Load configuration
require_once('config.php');
@ini_set("max_execution_time","0");
error_reporting(E_ALL);
ini_set('display_errors', 1);

function feed_generator($name, $url) {

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    if (!$output = curl_exec($curl)) {
        error_log(date('Y-m-d H:i:s - ', time()) . 'Could not open ' . $url ."\n", 3, DIR_LOGS . $name . '.txt');

        return false;
    }

   # libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($output);
    //Save XML as a file
    $dom->save(DIR_ROOT . 'sitemap.xml');
    return true;
}

// Run feeds
feed_generator('sitemap', HTTPS_SERVER . 'index.php?route=feed/google_sitemap');
