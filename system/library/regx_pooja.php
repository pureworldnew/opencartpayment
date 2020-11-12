<?php
$sub="matgdf123fhgabch";
$pattern='/\d/';
preg_match($pattern, $sub, $result); 
pr($result);
function pr($data){
echo "<pre>";
print_r($data);
echo "</pre>";
}
 ?>
