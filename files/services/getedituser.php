<?php
@session_start();

include("connection.php");

if($_REQUEST['enduserid']!='')
{
	$concat = " AND c.sno='".$_REQUEST['enduserid']."'";
}

$query="select c.sno,c.name, c.groupid,c.dept, c.designation, c.active from U_endusers c where c.ownerid=".$_SESSION['ownerid']." ".$concat." order by c.sno asc";
$result = $conn->query($query);


$countrec =  $result->num_rows;
$arr = array();
if($countrec>0)
{
	while($row = $result->fetch_assoc())
	{
		$arr[] = $row;	
	}
}




# JSON-encode the response
$json_response = json_encode($arr);

// # Return the response
echo $json_response;
$conn->close();
?>
