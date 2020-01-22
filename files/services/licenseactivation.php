<?php
@session_start();
include("connection.php");

$concatsearchres="";
$concatstr="";
	
if(isset($_REQUEST['mode']))
{
	if($_REQUEST['mode']=='releaseuser')
	{
		$endUserQuery = $conn->query("select * from U_endusers where ownerid='".$_SESSION['ownerid']."' AND active=2 AND licenseflag='1'");
		$endUserNum = $endUserQuery->num_rows;
		
		$currentdate=date('Y-m-d',time());
		if($endUserNum>0)
		{
			$arr[]=array("sno"=>"0",
					"name"=>"New User");
			while($row=$endUserQuery->fetch_assoc())
			{	
				$arr[]=array("sno"=>$row['sno'],
					"name"=>$row['sno']."-".$row['name'],
					"selected"=> 0
				);
			}
			echo json_encode($arr);
		}
	}
    if($_REQUEST['mode']=='liccount')
	{	
		$countli = $conn->query("select sum(total_lic) as total_lic from U_license where owner_id='".$_SESSION['ownerid']."'");
		$countlic 		= @$countli->fetch_assoc();
		$totallic 		= $countlic['total_lic'];
		$usedlici 		= $conn->query("select count(sno) as used_lic from U_endusers where licenseflag=1 AND active=1 AND ownerid='".$_SESSION['ownerid']."' AND deleteuser=0");
		$usedlicquery 	= @$usedlici->fetch_assoc();
		$usedlic 		= $usedlicquery['used_lic'];
		
		$available_lic 	= intval($totallic)-intval($usedlic);
		
		echo $available_lic;
	}
	if($_REQUEST['mode']=='filter')
	{
		$page = $_REQUEST['page'];
		$entrylimit = $_REQUEST['entrylimit'];
		$startlimit=$page*$entrylimit-$entrylimit;
		$endlimit=$page*$entrylimit;
		$query = "select * from U_endusers where 1 AND (name like '%".$_REQUEST['searchitem']."%' OR dept like '%".$_REQUEST['searchitem']."%') AND deleteuser=0 AND active=0 ".$concatstr." order by jdt desc limit ".$startlimit.",".$endlimit;
		$enduser_profile_details = $conn->query($query);
		
		$enduser_num = $enduser_profile_details->num_rows;
		
		$currentdate=date('Y-m-d',time());
		if($enduser_num>0)
		{
			while($row=$enduser_profile_details->fetch_assoc())
			{	$startlimit++;	
				$sno = $row['sno'];
				$name = $row['name'];
				$pcname = $row['pcname'];
				$jdt = $row['jdt'];		
				$licenseflag = $row['active'];
				$concatsearchres.= '{"serialnum":"'.$startlimit.'","id":"'.$sno.'","name":"'.$name.'","jdt":"'.$jdt.'","pcname":"'.$pcname.'","active":"'.$licenseflag.'"},';
			}
		
			$concatsearchres=substr($concatsearchres, 0, -1);
			if(isset($_REQUEST['userid']))
			{
				$concatsearchres=$concatsearchres;
			}
			else
			{
				$concatsearchres='['.$concatsearchres.']';
			}
		}
		else
		{
			$concatsearchres='[]';
		}
		# JSON-encode the response
		echo $concatsearchres;
	}
	else if($_REQUEST['mode']=='totalcount')
	{
		$totalit = $conn->query("select sno from U_endusers where active=0 AND deleteuser=0 AND ownerid='".$_SESSION['ownerid']."'");
		$totalitems = @$totalit->num_rows;
		echo $totalitems;
	}
	else if($_REQUEST['mode']=='edituser')
	{
		$countli 		= $conn->query("select sum(total_lic) as total_lic from U_license where owner_id='".$_SESSION['ownerid']."'");
		$countlic 		= @$countli->fetch_assoc();
		$totallic 		= $countlic['total_lic'];
		$usedlicquy = $conn->query("select count(sno) as used_lic from U_endusers where ownerid='".$_SESSION['ownerid']."' AND deleteuser=0");
		$usedlicquery 	= @$usedlicquy->fetch_assoc();
		$usedlic 		= $usedlicquery['used_lic'];
		
		$available_lic 	= intval($totallic)-intval($usedlic);
		
		if($available_lic < $totallic)
		{
			$editend_user_query = "update U_endusers set licenseflag=1,active=1 where sno='".$_REQUEST['userid']."'";
			$update_user_profile = $conn->query($editend_user_query);
			if($update_user_profile)
			{
				echo "1";
			}
			else 
			{ 
				echo "0"; 
			}
		}
		else
		{
			echo "0";
		}
		
		
		
	}
	else if($_REQUEST['mode']=='deleteuser')
	{
		$updatequery = "update U_endusers set deleteuser='1' where sno='".$_REQUEST['userid']."'";
		$update_user_profile = $conn->query($updatequery);
		if($update_user_profile)
		{
			echo "1";
		}
		else
		{
			echo "0";
		}
	}	
}
else 
{
	$page = $_REQUEST['page'];
	$entrylimit = $_REQUEST['entrylimit'];
	$startlimit=$page*$entrylimit-$entrylimit;
	$endlimit=$page*$entrylimit;
	
	if(isset($_SESSION['ownerid']))
	{
		$ownerid = $_SESSION['ownerid'];
		$concatstr.=" AND ownerid=".$ownerid;
	}
	$query = "select * from U_endusers where 1 AND deleteuser=0 AND active=0 ".$concatstr." order by jdt desc limit ".$startlimit.",".$endlimit;
	$enduser_profile_details = $conn->query($query);
	
	$enduser_num = $enduser_profile_details->num_rows;
	
	$currentdate=date('Y-m-d',time());
	if($enduser_num>0)
	{
		while($row=$enduser_profile_details->fetch_assoc())
		{	$startlimit++;	
			$sno = $row['sno'];
			$name = $row['name'];
			$pcname = $row['pcname'];
			$jdt = $row['jdt'];		
			$licenseflag = $row['active'];
		 	$concatsearchres.= '{"serialnum":"'.$startlimit.'","id":"'.$sno.'","name":"'.$name.'","jdt":"'.$jdt.'","pcname":"'.$pcname.'","active":"'.$licenseflag.'"},';
		}
	
		$concatsearchres=substr($concatsearchres, 0, -1);
		if(isset($_REQUEST['userid']))
		{
			$concatsearchres=$concatsearchres;
		}
		else
		{
			$concatsearchres='['.$concatsearchres.']';
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