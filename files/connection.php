<?php
@session_start();
//error_reporting(0);
$conn = new mysqli("localhost", "root", "", "app_endetect");
//$conn = new mysqli("127.0.0.1", "admin_root", "Tellugu@10125", "admin_app");
date_default_timezone_set("Asia/Kolkata");

define("baseurl", "http://localhost/www/app_endetect_new/");
// $domainname = "https"."://".$_SERVER['HTTP_HOST']."/";
// define("baseurl", $domainname);
?>
