<?php
$seed = str_split('abcdefghijklmnopqrstuvwxyz'
.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'); // and any other characters
shuffle($seed); // probably optional since array_is randomized; this may be redundant
$rand = '';
foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
 
$licensekey =  $rand;
?>