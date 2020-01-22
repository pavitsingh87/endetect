<?php
if(isset($_REQUEST['notifyme']))
{
	include("connection.php");
	if(@$_REQUEST['notifyme']=='1')
	{
		$userdetquery 	= 	"select * from U_getaction where ownerid='".$_REQUEST['ownerid']."' AND notify='0' AND status='1'";
		$executequery 	= 	$conn->query($userdetquery);
		$exenum 		= 	$executequery->num_rows;
		echo $exenum;
	}
	$conn->close();

}
?>