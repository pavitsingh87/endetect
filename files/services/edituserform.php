<?php 
@session_start();
include("connection.php");
$getuserdet = $conn->query("select * from U_endusers where sno='".$_REQUEST['enduserid']."'");
$getuserarr = $getuserdet->fetch_assoc();
?>
