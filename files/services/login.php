<?php
include_once('../connection.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_query = $conn->query("select * from U_endowners where email='" . $_REQUEST['email'] . "' AND password='" . md5($_REQUEST['password']) . "' AND active=1 AND noauth=1");

$login_num   = $login_query->num_rows;
$login_array = $login_query->fetch_assoc();
$ipaddress   = $_SERVER['REMOTE_ADDR'];
//$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$hostname = "";

if ($login_num > 0) {
    $updatelog = $conn->query("insert into ownerlog set email='" . $_REQUEST['email'] . "',status='success',dateapp='" . date("Y-m-d H:i:s", time()) . "',ipaddress='" . $ipaddress . "',hostname='" . $hostname . "'");
    //$sessionupdate = $conn->query("select * from endowner_session where sessionid='".$_."'");
    $sessionid                = session_id();
    $_SESSION['email']        = $login_array['email'];
    $_SESSION['name']         = $login_array['name'];
    $_SESSION['ownerid']      = $login_array['sno'];
    $_SESSION['company']      = $login_array['company'];
    $_SESSION['ownersession'] = $sessionid;

    $insertownersession = $conn->query("insert into endowner_session set
            endownerid='" . $login_array['sno'] . "',
            sessionid='" . $sessionid . "',
            status='1',
            datetimecreated='" . Date("Y-m-d H:i:s", time()) . "',
            ipaddress='" . $_SERVER["REMOTE_ADDR"] . "'
            ");
    $last_id = $conn->insert_id;
    //update all previous session for this owner
    $updateownersession = $conn->query("update endowner_session set status=0 where id<$last_id AND endownerid='" . $login_array["sno"] . "' AND status=1");
    header("location:../index.php");
} else {
    $updatelog = $conn->query("insert into ownerlog set email='" . $_REQUEST['email'] . "',status='failed',dateapp='" . date("d-m-Y H:i:s", time()) . "',ipaddress='" . $ipaddress . "',hostname='" . $hostname . "'");
    header("location:../login.php?error=1");
}
?>
