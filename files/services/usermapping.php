<?php
@session_start();
include("connection.php");

$concatsearchres="";
$concatstr="";

if(isset($_REQUEST['mode']))
{	
	if($_REQUEST['mode']=='edituser')
	{		
		$userd = $conn->query("select profilepic from U_endusers where sno='".$_REQUEST['userid']."'");
		$userdet = $userd->fetch_assoc();
		$editend_user_query = "update U_endusers set name='".$_REQUEST['endusername']."',dept='".$_REQUEST['department']."',designation='".$_REQUEST['designation']."',groupid='".$_REQUEST['groupname']."' where sno='".$_REQUEST['userid']."'";
		$update_user_profile = $conn->query($editend_user_query);
		if($update_user_profile)
		{
			echo "Profile Updated";
		}
	}
	else if($_REQUEST['mode']=='useract')
	{
		if($_REQUEST['active']=='red')
		{
			$updatequery = $conn->query("update U_endusers set active='1' where sno='".$_REQUEST['userid']."'");
			echo "1";
		}
		if($_REQUEST['active']=='green')
		{
			$updatequery = $conn->query("update U_endusers set active='0' where sno='".$_REQUEST['userid']."'");
			echo "1";
		}
	}
	else if($_REQUEST['mode']=='delete')
	{
		$updatequery = $conn->query("update U_endusers set deleteuser='1' where sno='".$_REQUEST['userid']."'");
	}
	
	else if($_REQUEST['mode']=='edit')
	{
		if(isset($_REQUEST['userid']))
		{
			$userid = $_REQUEST['userid'];
			$concatstr.=" AND sno=".$userid;
		}
		if(isset($_SESSION['ownerid']))
		{
			$ownerid = $_SESSION['ownerid'];
			$concatstr.=" AND ownerid=".$ownerid;
		}
		$query = "select * from U_endusers where 1 AND licenseflag=1 AND deleteuser=0".$concatstr." order by jdt desc";
		$enduser_profile_details = $conn->query($query);
		
		$enduser_num = $enduser_profile_details->num_rows;
		
		$currentdate=date('Y-m-d',time());
		if($enduser_num>0)
		{
			while($row=$enduser_profile_details->fetch_assoc())
			{
				$sno = $row['sno'];
				$profilepic = $row['profilepic'];
				if($profilepic=='')
				{
					$profilepic="uploads/";
				}
				$name = $row['name'];
				$dept = $row['dept'];
				$designation = $row['designation'];
				$jdt = $row['jdt'];
				$pcname= $row['pcname'];
				if($row['active']=='1')
				{
					$statuscolor = "green";
				}
				else
				{
					$statuscolor = "red";
				}
				$status = $row['active'];
				$pcname = $row['pcname'];
				$macaddress = $row['macaddress'];
				
				$concatsearchres.= '{"id":"'.$sno.'","profilepic":"'.$profilepic.'","name":"'.$name.'","jdt":"'.$jdt.'","pcname":"'.$pcname.'","dept":"'.$dept.'","status":"'.$statuscolor.'","designation":"'.$designation.'","macaddress":"'.$macaddress.'"},';
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
}
else 
{
	if(isset($_REQUEST['userid']))
	{
		$userid = $_REQUEST['userid'];
		$concatstr.=" AND sno=".$userid;
	}
	if(isset($_SESSION['ownerid']))
	{
		$ownerid = $_SESSION['ownerid'];
		$concatstr.=" AND ownerid=".$ownerid;
	}
	$query = "select * from U_endusers where 1 AND licenseflag=1 AND deleteuser=0".$concatstr." order by jdt desc";
	$enduser_profile_details = $conn->query($query);
	
	$enduser_num = $enduser_profile_details->num_rows;
	
	$currentdate=date('Y-m-d',time());
	if($enduser_num>0)
	{
		while($row=$enduser_profile_details->fetch_assoc())
		{		
			$sno = $row['sno'];
			$profilepic = $row['profilepic'];
			if($profilepic=='')
			{
				$profilepic="uploads/";
			}
			$name = $row['name'];
			$dept = $row['dept'];
			$designation = $row['designation'];
			$jdt = $row['jdt'];
			if($row['active']=='1')
			{
				$statuscolor = "green";
			}
			else
			{
				$statuscolor = "red";
			}
			$status = $row['active'];
			$pcname = $row['pcname'];
			$macaddress = $row['macaddress'];
			$groupid = $row['groupid'];
			$groupname = $row['groupname'];
		 	$concatsearchres.= '{"id":"'.$sno.'","profilepic":"'.$profilepic.'","name":"'.$name.'","jdt":"'.$jdt.'","pcname":"'.$pcname.'","dept":"'.$dept.'","status":"'.$statuscolor.'","designation":"'.$designation.'","groupid":"'.$groupid.'","groupname":"'.$groupname.'","macaddress":"'.$macaddress.'"},';
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