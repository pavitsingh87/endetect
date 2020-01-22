<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
include("connection.php");
date_default_timezone_set("Asia/Kolkata");
# Include the Autoloader (see "Libraries" for install instructions)

$data = json_decode(file_get_contents('php://input'), true);
foreach ($data as $key => $value) {
   $stdArray[] = (array) $value;
}
function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}
$sno= urldecode($stdArray['0']['0']);
$newsno= urldecode($stdArray['1']['0']);
$enduserid= urldecode($stdArray['2']['0']);
$endownerid= urldecode($stdArray['3']['0']);
$dtype= urldecode($stdArray['4']['0']);
$ipaddress= urldecode($stdArray['5']['0']);
$hostname= urldecode($stdArray['6']['0']);
$ddate= urldecode($stdArray['7']['0']);
$displayname= urldecode($stdArray['8']['0']);
$publisher= urldecode($stdArray['9']['0']);
$estimatedsize= urldecode($stdArray['10']['0']);
$installdate= urldecode($stdArray['11']['0']);
$comments= urldecode($stdArray['12']['0']);
// installed software in mysql
$updatesql = $conn->query("update enduserinstalledsoftware set installflag='0' where enduserid='".$enduserid."'");
$checkquery = $conn->query("select * from enduserinstalledsoftware where displayname='".$displayname."' AND enduserid='".$enduserid."'");
if($checkquery->num_rows>0)
{
	$updatemysql = $conn->query("update enduserinstalledsoftware set installflag='1' where enduserid='".$enduserid."' AND displayname='".$displayname."'");
}
else
{
	$insertmysql = $conn->query("insert into enduserinstalledsoftware set sno='".$sno."',
	newsno='".$newsno."',
	enduserid='".$enduserid."',
	endownerid='".$endownerid."',
	dtype='".$dtype."',
	ipaddress='".$ipaddress."',
	hostname='".$hostname."',
	ddate='".strtotime($ddate)."',
	displayname='".$displayname."',
	publisher='".$publisher."',
	estimatedsize='".$estimatedsize."',
	installdate='".$installdate."',
	comments='".$comments."',
	installflag='1'");
}
?>