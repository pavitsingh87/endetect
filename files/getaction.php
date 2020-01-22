<?php
include("connection.php");
$getact_query = "select sno from U_getaction where enduserid='".$_REQUEST['enduserid']."' AND status='0' order by sno desc limit 1 ";

$getact_exe = $conn->query($getact_query);

$getact_num = $getact_exe->num_rows;

if($getact_num>0)
{
	$getact_arr = $getact_exe->fetch_assoc();
	
	echo "21#".$getact_arr['sno'];
}

?>