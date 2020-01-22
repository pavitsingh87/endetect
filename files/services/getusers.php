<?php
@session_start();
include("connection.php");

$query="select c.profilepic,c.sno,c.name, c.dept, c.pcname, c.groupid, c.designation,c.macaddress,c.pcname, Date_Format(c.jdt,'%d-%m-%y') as jdt,c.active from U_endusers c where c.ownerid=".$_SESSION['ownerid']." AND active=1 AND licenseflag=1 order by c.sno desc";
$result = $conn->query($query);


$arr = array();
if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$arr[] = $row;	
	}
}
# JSON-encode the response
$json_response = json_encode($arr);

// # Return the response
echo $json_response;


?>
