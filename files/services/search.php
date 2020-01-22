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
	$concatstr .='{ "term" : { "endownerid" : "'.$_SESSION['ownerid'].'" } }'; 
}
if(isset($_REQUEST['q']))
{
	$concatstr .=',{"term" : { "ddata" : "'.$_REQUEST['q'].'" } }';
}
if(isset($_REQUEST['userid']))
{
	$concatstr .=',{ "term" : { "enduserid" : "'.$_REQUEST['userid'].'" } }';
}
if(isset($_REQUEST['fdate']))
{
	$startdate	= strtotime($_REQUEST['fdate']."00:00:00");
	$pavenddates	= strtotime($_REQUEST['pavdate']."23:59:59");
	$concatstr .=',{ "range" : { "ddate" : { "gte" : "'.$startdate.'","lte":"'.$pavenddates.'" } } }';
}

/*
{
	"filtered" : {
	"filter" : {
	"bool" : {
	"must" : [
	{ "term" : { "ddata" : "category" } },
	{ "term" : { "enduserid" : "1" } },
	{ "term" : { "endownerid" : "3" } },
	{ "range" : { "ddate" : { "gte" : "1415871634","lte":"1415878429" } } }
	]
}
}
}
}
}*/

$searchParams['body']= '{
    "query": {
        "bool" : {
            "filter" : {
                 "bool" : {
                    "must" : [
                     '.$concatstr.'
                    ]
                }
            }
        }
    }
}';

$retDoc = $client->search($searchParams);



if($retDoc['hits']['total']>=1)
{
	$results=$retDoc['hits']['hits'];
}
$concatsearchres="";
if(isset($results))
{
	foreach($results as $r)
	{
		$datefind = $r['_source']['ddate'];
		$day = date("d",$datefind);
		$month = date("M",$datefind);
		$year = date("Y",$datefind);
		$concatsearchres.= '{"mouse_clicks":"'.$r['_source']['mouse_clicks'].'","other":"'.$r['_source']['other'].'","ddate":"'.$r['_source']['ddate'].'","sno":'.$r['_source']['sno'].',"app_title":"'.$r['_source']['app_title'].'","endversion":"'.$r['_source']['endversion'].'","dtype":"'.$r['_source']['dtype'].'","enduserid":"'.$r['_source']['enduserid'].'","_id":"'.$r['_source']['_id'].'","endownerid":"'.$r['_source']['endownerid'].'","ddata":"'.$my_string = str_replace(array('®', '™'), array('', ''), mysql_real_escape_string(htmlspecialchars($r['_source']['ddata']))).'","day":"'.$day.'","month":"'.$month.'","year":"'.$year.'"},';	
		
	}
	$concatsearchres=substr($concatsearchres, 0, -1);
}
$concatsearchres='['.$concatsearchres.']';
# JSON-encode the response
echo $json_response = $concatsearchres;

?>

