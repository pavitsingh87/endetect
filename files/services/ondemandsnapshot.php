<?php
include("connection.php");
if((isset($_REQUEST['loadimage'])) && ($_REQUEST['loadimage']=='1'))
{
	if(strlen($_REQUEST['loadimage'])>0)
	{
		$userdet = $conn->query("select * from U_getaction where sno='".$_REQUEST['actionid']."'");
		$actionarray 	= $userdet->fetch_assoc();
		if($actionarray['actionpath']!='')
		{
			$queryima = $conn->query("select profilepic,name,designation,dept,groupid from U_endusers where sno='".$actionarray['enduserid']."'");
			$queryimage = @$queryima->fetch_assoc();
			echo $actionarray['actionpath'].",".$actionarray['apptitle'].",".$queryimage['name'].",".$queryimage['designation'];	
		}
	}
}
if((isset($_REQUEST['updatenotify'])) && ($_REQUEST['updatenotify']=='1'))	
{
	$updatenotify = $conn->query("update U_getaction set status='1',notify='1' where sno='".$_REQUEST['actionid']."'");
	if($updatenotify)
	{
		echo "1";
		
	}
}
$conn->close();
?>