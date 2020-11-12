<?php 
$ch = curl_init('https://www.howsmyssl.com/a/check');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);

$json = json_decode($data);
echo "TLS Version : " . $json->tls_version . "<br>";

$curl_info = curl_version();
echo "SSL Version : " . $curl_info['ssl_version'];