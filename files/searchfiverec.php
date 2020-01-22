<?php
@session_start();
include("connection.php");
include("../vendor/autoload.php");
$client = new Elasticsearch\Client();
$searchParams['index'] = 'mongoindex';
$searchParams['type']  = 'u_endata';

if($_SESSION['ownerid']!='')
{
	$ownerg = $_SESSION['ownerid'];
	$concatstr .='{ "term" : { "endownerid" : "'.$ownerg.'" } }';
}
if(isset($_REQUEST['enduserid']))
{	
	if($_REQUEST['enduserid']>'0')
	{
		$enduserid = $_REQUEST['enduserid'];
		$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
	}
}

$concatstr .=',{ "term" : { "delete" : "1" } }';
/*
if($_REQUEST['prevnext']=='0')
{
	$sort = '"sort" :[ { "sno" : {"order" : "desc"}} ],';
	
	$range = ', { "range" : { "sno" : { "lt" : "'.$sno.'" } } } ';
}
else if($_REQUEST['prevnext']=='0')
{
	$sort = '"sort" :[ { "sno" : {"order" : "desc"}} ],';
	$range = ', { "range" : { "sno" : { "lt" : "'.$sno.'" } } } ';
}*/
echo $searchParams['body']= '{
		"size" : 2,
		"sort" :[ { "sno" : {"order" : "desc"} } ],
		"query": {
			"filtered" : {
				"filter" : {
					"bool" : {
						"must" : [
							'.$concatstr.$range.'
						]
					}
				}
			}
		}
	}';


$searchParams1['body']= '{
		"size" : 2,
		'.$sort.'
		"query": {
			"filtered" : {
				"filter" : {
					"bool" : {
						"must" : [
							'.$concatstr.$range.'
						]
					}
				}
			}
		}
	}';

$searchParams2['body']= '{
		"size" : 2,
		'.$sort.'
		"query": {
			"filtered" : {
				"filter" : {
					"bool" : {
						"must" : [
							'.$concatstr.$range.'
						]
					}
				}
			}
		}
	}';


$retDoc = $client->search($searchParams);
$retDoc1 = $client->search($searchParams1);
$retDoc2 = $client->search($searchParams2);



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
	
}

if($retDoc1['hits']['total']>=1)
{
	$results=$retDoc1['hits']['hits'];
}
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
	
}

if($retDoc2['hits']['total']>=1)
{
	$results=$retDoc2['hits']['hits'];
}
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

mysql_close($db_connect);
?>

