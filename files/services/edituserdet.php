 <?php
include_once("connection.php");

if(trim($_REQUEST['delete'])=='1')
{
	$updateuserdetquery = $conn->query("update U_endusers set active=0,deleteuser=1,licenseflag=0 where sno='".$_REQUEST['enduserid']."'");
	$usedli = $conn->query("select * from U_endusers where sno='".$_REQUEST['enduserid']."'");
	$usedlicquery 	= $usedli->fetch_assoc();
	$updateuserdetquery = $conn->query("insert into U_getaction set apptitle='Delete User',enduserid='".$_REQUEST['enduserid']."',action='51',ownerid='".$usedlicquery['ownerid']."',actiondate='".Date('Y-m-d H:i:s',time())."',status=1,ipaddress='".$_SERVER['REMOTE_ADDR']."',hostname='".$_SERVER['HTTP_HOST']."'");
    $conn->query("UPDATE U_license SET license_used = license_used - 1 WHERE owner_id = '".$usedlicquery['ownerid']."'");
	echo json_encode(array('status' => 'success'));
}
else if(trim($_REQUEST['active'])=='1')
{
	$updateuserdetquery = $conn->query("update U_endusers set active=1,licenseflag=1,mergeid='".$_REQUEST["mergeid"]."' where sno='".$_REQUEST['enduserid']."'");
	$updatemergequery = $conn->query("update U_endusers set active=3,licenseflag=0 where sno='".$_REQUEST['mergeid']."'");
	$ch = curl_init();
	echo json_encode(array('status' => 'success'));
	$enduserid = base64_encode($_REQUEST["enduserid"]);
	$mergeid=base64_encode($_REQUEST["mergeid"]);
    $pathTemp = baseurl ."update_by_query.php?enduserid=". $enduserid ."&mergeid=". $mergeid;
	file_get_contents($pathTemp);

}
else if(trim($_REQUEST['active'])=='0')
{
	$updateuserdetquery = $conn->query("update U_endusers set active=0 where sno='".$_REQUEST['enduserid']."'");
	echo json_encode(array('status' => 'success'));
}
else if(trim($_REQUEST['active'])=='2')
{
	$updateuserdetquery = $conn->query("update U_endusers set active=2 where sno='".$_REQUEST['enduserid']."'");
	echo json_encode(array('status' => 'success'));
}
else if(trim($_REQUEST['edituser'])=='1')
{
	$updateuserdetquery = $conn->query("update U_endusers set name='".$_REQUEST['name']."',groupid='".$_REQUEST['group']."',dept='".$_REQUEST['dept']."',designation='".$_REQUEST['designation']."' where sno='".$_REQUEST['enduserid']."'");
	echo json_encode(array('status' => 'success'));
}
else
{
	echo json_encode(array('status' => 'error'));
}
?>
