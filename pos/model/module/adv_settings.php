<?php $ldata = TRUE;
$iserver = $_SERVER['SERVER_NAME'];
function getLicensedDomain() {
    $domain = $_SERVER['SERVER_NAME'];
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}
$lextension_name = $adv_ext_name;
$lextension = $adv_ext_short_name;
$lversion = $adv_ext_version;
$loc_version = VERSION;
$lip = $_SERVER['REMOTE_ADDR'];
if ($iserver != 'localhost' && $iserver !='km-technologies.net' && $iserver != 'localhost' && $lip != '::1' && (strpos($lip, '127.0.') === false) && (strpos($lip, '10.0.') === false) && (strpos($lip, '192.168.') === false)) {
    $servers = '';
    $lstatus = '';
    $llicense = '';
    $ldomain = '';
    $laccess = TRUE;
} else {
    $servers = '';
    $lstatus = '';
    $llicense = '';
    $ldomain = '';
    $laccess = TRUE;
}
function generate_key_string() {
    $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $segment_chars = 5;
    $num_segments = 4;
    $key_string = '';
    for ($i = 0;$i < $num_segments;$i++) {
        $segment = '';
        for ($j = 0;$j < $segment_chars;$j++) {
            $segment.= $tokens[rand(0, 35) ];
        }
        $key_string.= $segment;
        if ($i < ($num_segments - 1)) {
            $key_string.= '-';
        }
    }
    return $key_string;
}
?>