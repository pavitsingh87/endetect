<?php
@session_start();
//error_reporting(0);
// $domainname = "https"."://".$_SERVER['HTTP_HOST']."/";
// define("baseurl", $domainname);
$db_host			= "127.0.0.1";
$db_name			= "app_endetect";
$db_username			= "root";
$db_password			= '';
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
date_default_timezone_set("Asia/Kolkata");
?>
