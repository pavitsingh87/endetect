<?php
include("connection.php");
$email=$_REQUEST['email'];
$sql=$conn->query("select * from U_endowners where email ='".$email."'");
$mail=$sql->num_rows;

if($mail){ 
    echo '1';
}
else{
    echo '0';
}
$conn->close();
?>