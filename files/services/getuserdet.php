<?php
@session_start();
include("connection.php");

# Include the Autoloader (see "Libraries" for install instructions)
use Elasticsearch\ClientBuilder;
require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();
$searchParams['index'] = 'mongoindex1';
$searchParams['type']  = 'u_endata';

if(isset($_SESSION['ownerid']))
{
	$ownerg = $_SESSION['ownerid'];
	$concatstr .='{ "term" : { "endownerid" : "'.$ownerg.'" } }';
}
if(isset($_REQUEST['enduserid']))
{
	if($_REQUEST['enduserid']!='0')
	{
		$enduserid = $_REQUEST['enduserid'];
		$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
	}
}

$userquery = $conn->query("select * from U_endusers where ownerid='".$_SESSION['ownerid']."' and sno='".$_REQUEST['enduserid']."'");
$concatsearchres="";

while ($row=$userquery->fetch_assoc())
{
	if($row['profilepic']=='')
	{
		$row['profilepic']="uploads/default.jpeg";
	}
	
	$joiningdate = date("d M Y",strtotime($row['jdt']));
	
	$concatsearchres.= '{"id":"'.$row['sno'].'","name":"'.$row['name'].'","dept":"'.$row['dept'].'","designation":"'.$row['designation'].'","activationdate":"'.$joiningdate.'","profilepic":"'.$row['profilepic'].'","typed_words":"'.$row['type_words'].'","copiedwords":"'.$row['copied_words'].'","files_copies":"'.$row['files_copies'].'","pendrivein":"'.$row['pendriveinsert'].'"},';
}

$concatsearchres=substr($concatsearchres, 0, -1);

echo $concatsearchres;
?>