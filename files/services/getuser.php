<?php
@session_start();
include("connection.php");

if(strlen(@$_REQUEST['enduserid'])>0)
	{
		$concat = " AND sno=".$_REQUEST['enduserid'];
		$userquery = $conn->query("select * from U_endusers where ownerid='".$_SESSION['ownerid']."'".$concat);
		$concatsearchres="";
		while ($row=$userquery->fetch_assoc())
		{
			if($row['profilepic']=='')
			{
				$row['profilepic']="uploads/default.jpeg";
			}
			$concatsearchres= '{"id":"'.$row['sno'].'","name":"'.$row['name'].'","profilepic":"'.$row['profilepic'].'"},';
		}
		
		$concatsearchres=substr($concatsearchres, 0, -1);
		
		echo $concatsearchres;
	}
	else
	{
		$userquery = $conn->query("select * from U_endusers where ownerid='".$_SESSION['ownerid']."'");
		$concatsearchres="";
		$concatsearchres.= '{"id":"0","name":"All","profilepic":""},';
		while ($row=$userquery->fetch_assoc())
		{
			if($row['profilepic']=='')
			{
				$row['profilepic']="uploads/default.jpeg";
			}
			$concatsearchres.= '{"id":"'.$row['sno'].'","name":"'.$row['name'].'","profilepic":"'.$row['profilepic'].'"},';
		}
		
		$concatsearchres=substr($concatsearchres, 0, -1);
		
		$concatsearchres='['.$concatsearchres.']';
		echo $concatsearchres;
	}

?>