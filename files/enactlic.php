<?php
include("connection.php");

$username = $_REQUEST['username'];
$ownerid = $_REQUEST['owner'];
$pcname = $_REQUEST['pcname'];
$macaddress = $_REQUEST['mac'];
$track	= htmlspecialchars_decode ($_REQUEST['tracks']);
$ipaddress = $_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$enduserid = $_REQUEST['enduserid'];
$ErrorMessage="";
$checklicense	= $conn->query("SELECT * FROM U_endowners where sno='".$ownerid."'");
$checklicnum	= $checklicense->num_rows;


if($checklicnum>0)
{
	$checklicarray		= $checklicense->fetch_assoc();
	$ownerid 			= $checklicarray['sno'];

	$currentdatetime	= date("Y-m-d h:i:s",time());
	
	if(strtotime($currentdatetime)>strtotime($checklicarray['exp_date']))
	{
		$ErrorMessage	= "200";
	}
	else if($checklicarray['suspended']=='1')
	{
		$ErrorMessage	= "300";
	}
	else if($checklicarray['active']=='0')
	{
		$ErrorMessage	= "400";
	}	
	else if(($checklicarray['active']=='1') && ($checklicarray['suspended']=='0'))
	{
		if($enduserid=="0")
		{
			if($checklicarray['noauth']=='1')
			{
				$InsertEnduser = $conn->query("insert into U_endusers set name='".$username."',ownerid='".$ownerid."',licenseflag=1,jdt='".$currentdatetime."',macaddress='".$macaddress."',trackkey='".$track."',pcname='".$pcname."',ipaddress='".$ipaddress."',hostname='".$hostname."'");
			}
			else
			{
			$InsertEnduser = $conn->query("insert into U_endusers set name='".$username."',ownerid='".$ownerid."',jdt='".$currentdatetime."',macaddress='".$macaddress."',trackkey='".$track."',pcname='".$pcname."',ipaddress='".$ipaddress."',hostname='".$hostname."'");
			}
			
			$EnduserId = $conn->insert_id;
			if(isset($EnduserId))
			{
				$ErrorMessage = "100&".$EnduserId;
			}
		}
		else
		{
			$checkEnduser = $conn->query("select * from U_endusers where sno='".$enduserid."' AND active=1");
			$numEnduser = $checkEnduser->num_rows;
			
			if($numEnduser>0)
			{
				$ErrorMessage="700";
			}
		}
	}
}
else
{
	$ErrorMessage	= "500";
}
echo $ErrorMessage;

$conn->close();
?>