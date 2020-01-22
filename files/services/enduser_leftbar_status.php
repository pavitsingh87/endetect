<?php
@session_start();
header("Access-Control-Allow-Origin: *");
include("../commonfunctions.php");
$concatsearchres="";
$concatstr="";
if($_REQUEST["mode"]=="abc")	
{
	$ownerid = $_SESSION['ownerid'];
	if($_REQUEST["fg"]=="1")
	{
		$lastaccesstime =  date('Y-m-d H:i:s', strtotime('-1 hours', time()));
		$query = "select * from U_endusers where 1 AND active=1 AND licenseflag=1 AND deleteuser=0 AND ownerid=".$ownerid." AND lastaccesstime>'".$lastaccesstime."' order by lastaccesstime desc";
	}
	else if($_REQUEST["fg"]=="2")
	{
		$lastaccesstime =  date('Y-m-d H:i:s', strtotime('-1 hours', time()));
		$lastaccesstime1 =  date('Y-m-d H:i:s', strtotime('-8 hours', time()));
		$query = "select * from U_endusers where 1 AND active=1 AND licenseflag=1 AND deleteuser=0 AND ownerid=".$ownerid." AND (lastaccesstime between '".$lastaccesstime1."' AND '".$lastaccesstime."') order by lastaccesstime desc";
	}
	else if($_REQUEST["fg"]=="3")
	{
		$lastaccesstime =  date('Y-m-d H:i:s', strtotime('-8 hours', time()));
		$query = "select * from U_endusers where 1 AND active=1 AND licenseflag=1 AND deleteuser=0 AND ownerid=".$ownerid." AND lastaccesstime<'".$lastaccesstime."' order by lastaccesstime desc";
	}
	
	$enduser_profile_details = $conn->query($query);

	$enduser_num = $enduser_profile_details->num_rows;

	$currentdate=date('Y-m-d',time());
	if($enduser_num>0)
	{
		while($row=$enduser_profile_details->fetch_assoc())
		{
			if($row['lastaccesstime']=="0000-00-00 00:00:00")
			{
				$lastaccess="";
			}
			else
			{
				$lastaccess 	= ago(strtotime($row['lastaccesstime']));
			}
			
			if($lastaccess=='ideal')
			{
				$lastaccess = ago1(strtotime($row['lastaccesstime']));
				$personalimg = "images/ideal.png";
			}
			else if($lastaccess=='offline')
			{
				$lastaccess = "";
				$personalimg = "images/offline.png";
			}
			else
			{
				$personalimg = "images/online.png";
			}
			
			$startuptime 	= date('Y-m-d',strtotime($row['startuptime']));
			
			$checklastaccess	= date('Y-m-d',strtotime($row['lastaccesstime']));
			
			if(($startuptime!=$currentdate) || ($checklastaccess!=$currentdate))
			{
				$startup='Offline';
				$personalimg = "images/offline.png";
			}
			else
			{
				$startup="Start up time : ".date('H:i A',strtotime($row['startuptime']));
			}
			
			
			 $concatsearchres.= '{"id":"'.$row['sno'].'","version":"'.$row['version'].'","active":"'.$personalimg.'","profilepic":"'.$row['profilepic'].'","username":"'.$row['name'].'","lastaccess":"'.$lastaccess.'","startup":"'.$startup.'","userloggedtime":"'.$row['lastaccesstime'].'","eid": "'.base64_encode($row["sno"]).'"},';
		}

		$concatsearchres=substr($concatsearchres, 0, -1);

		$concatsearchres='['.$concatsearchres.']';
	}
	else
	{
		$concatsearchres='[]';
	}
	# JSON-encode the response
	echo $concatsearchres;
}
else
{
	if(isset($_REQUEST['filtertimeid']))
	{
		$timefilter = $conn->query("select * from filter_timerange where id='".$_REQUEST['filtertimeid']."'");
		$timefilterquery= $timefilter->fetch_assoc();
		$time = date("Y-m-d H:i:s",time());
		$lastlogindiff = date("Y-m-d H:i:s", strtotime($timefilterquery['timerangefilter'], strtotime($time)));	
		
		$concatstr.=" AND lastaccesstime BETWEEN '".$lastlogindiff."' AND '".$time."'";
	}

	if(isset($_SESSION['ownerid']))
	{
		$query = "select * from U_endusers where 1 AND active=1 AND licenseflag=1 AND deleteuser=0 AND ownerid=".$ownerid." order by lastaccesstime desc";
		$enduser_profile_details = $conn->query($query);

		$enduser_num = $enduser_profile_details->num_rows;

		$currentdate=date('Y-m-d',time());
		if($enduser_num>0)
		{
			while($row=$enduser_profile_details->fetch_assoc())
			{
				if($row['lastaccesstime']=="0000-00-00 00:00:00")
				{
					$lastaccess="";
				}
				else
				{
					$lastaccess 	= ago(strtotime($row['lastaccesstime']));
				}
				
				if($lastaccess=='ideal')
				{
					$lastaccess = ago1(strtotime($row['lastaccesstime']));
					$personalimg = "images/ideal.png";
				}
				else if($lastaccess=='offline')
				{
					$lastaccess = "";
					$personalimg = "images/offline.png";
				}
				else
				{
					$personalimg = "images/online.png";
				}
				
				$startuptime 	= date('Y-m-d',strtotime($row['startuptime']));
				
				$checklastaccess	= date('Y-m-d',strtotime($row['lastaccesstime']));
				
				if(($startuptime!=$currentdate) || ($checklastaccess!=$currentdate))
				{
					$startup='Offline';
					$personalimg = "images/offline.png";
				}
				else
				{
					$startup="Start up time : ".date('H:i A',strtotime($row['startuptime']));
				}
				
				
				 $concatsearchres.= '{"id":"'.$row['sno'].'","version":"'.$row['version'].'","active":"'.$personalimg.'","profilepic":"'.$row['profilepic'].'","username":"'.$row['name'].'","lastaccess":"'.$lastaccess.'","startup":"'.$startup.'","userloggedtime":"'.$row['lastaccesstime'].'"},';
			}

			$concatsearchres=substr($concatsearchres, 0, -1);

			$concatsearchres='['.$concatsearchres.']';
		}
		else
		{
			$concatsearchres='[]';
		}
	}
	else
	{
		$concatsearchres='[]';
	}
	# JSON-encode the response
	echo $concatsearchres;
}

$conn->close();
?>