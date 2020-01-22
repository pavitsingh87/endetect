<?php
@session_start();
include("connection.php");
if($_REQUEST['getuser']=='1')
{
	$checkquery = $conn->query("select * from U_group where ownerid='".$_SESSION['ownerid']."'");
	while($row=$checkquery->fetch_assoc())
	{
		$concatsearchres.= '{"id":"'.$row['id'].'","groupname":"'.$row['groupname'].'","ownerid":"'.$row['ownerid'].'"},';	
	}
	$concatsearchres=substr($concatsearchres, 0, -1);
	
	$concatsearchres='['.$concatsearchres.']';
	echo $concatsearchres;
}
else
{

	$checkquery = $conn->query("select * from U_group where groupname='".strtolower($_REQUEST['groupname'])."' AND ownerid='".$_SESSION['ownerid']."'");
	echo $checknum = $checkquery->num_rows; 
	
	if($checknum=='0')
	{
		$selectquery = $conn->query("insert into U_group set groupname='".strtolower($_REQUEST['groupname'])."', ownerid='".$_SESSION['ownerid']."'");
		
		echo $conn->insert_id;
	}
	else
	{
		echo "0";
	}
}
?>
