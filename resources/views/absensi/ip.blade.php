<?php

ob_start(); // Turn on output buffering
system('ifconfig');
$mycom=ob_get_contents();
ob_clean();
$findme = "Physical";

$pmac = strpos($mycom, $findme);
$mac=substr($mycom,($pmac+36),17); 
echo $mac;

?>
