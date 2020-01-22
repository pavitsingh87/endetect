<?php 
include("connection.php");
$checkquery = $conn->query("select * from U_endowners where sno='".$_SESSION['ownerid']."' AND password='".md5($_REQUEST['currentpassword'])."'");
$checknum = $checkquery->num_rows;

if(trim($_REQUEST['currentpassword'])=='')
{
	$errormess = "4";
}

else if(trim($_REQUEST['changepass1'])=='')
{
	$errormess = "5";
}
else if(trim($_REQUEST['changepass2'])=='')
{
	$errormess = "6";
}
else if(trim($_REQUEST['currentpassword'])==trim($_REQUEST['changepass1']))
{
	$errormess="7";
}
if(trim($errormess)=='')
{
	if($checknum>0)
	{
		if(strcmp($_REQUEST['changepass1'], $_REQUEST['changepass2'])=='0')
		{
			$updatepassword = $conn->query("update U_endowners set password='".md5($_REQUEST['changepass1'])."'  where sno='".$_SESSION['ownerid']."'");
			if(isset($updatepassword))
			{
				// update owner session status 0 when password change so that in future owner logouts
				$login_query	= $conn->query("update endowner_session set status='0' where endownerid='".$_SESSION['ownerid']."' AND status=1");
				$errormess = "1";
			}
		}
		else 
		{ 
			$errormess = "3";
		}	
	}
	else 
	{
		$errormess = "2";
	}
}
echo $errormess;
?>