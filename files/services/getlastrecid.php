<?php

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

$searchParams['body']= '{
	"from" : 0,
	 "size" : 1,
	 "sort" :[
        { "ddate" : {"order" : "desc"}}	]
}';

$retDoc = $client->search($searchParams);

if($retDoc['hits']['total']>=1)
{
	$results=$retDoc['hits']['hits'];
}

if(isset($results))
{
	foreach($results as $r)
	{
		$datefind = $r['_source']['sno'];
	}
}
echo $datefind;
$conn->close();

?>