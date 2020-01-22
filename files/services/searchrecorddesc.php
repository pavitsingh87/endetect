<?php
@session_start();
include("connection.php");
# Include the Autoloader (see "Libraries" for install instructions)
use Elasticsearch\ClientBuilder;
require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();
$searchParams['index'] = 'mongoindex1';
$searchParams['type']  = 'u_endata';


$searchParams['body']= '{

"size":"4",
"sort": [ {"sno":{"order":"desc"}} ],
"query" : {"bool" : {"query":{"match":{"enduserid":"'.$_REQUEST['enduserid'].'"}},"filter": {"range": { "sno": { "lt": "'.$_REQUEST['recordid'].'" }}}}}

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
		$concatsearchres.= '{"mouse_clicks":"'.$r['_source']['mouse_clicks'].'","other":"'.$r['_source']['other'].'","ddate":"'.$r['_source']['ddate'].'","sno":'.$r['_source']['sno'].',"app_title":"'.$r['_source']['app_title'].'","endversion":"'.$r['_source']['endversion'].'","dtype":"'.$r['_source']['dtype'].'","enduserid":"'.$r['_source']['enduserid'].'","_id":"'.$r['_source']['_id'].'","endownerid":"'.$r['_source']['endownerid'].'","ddata":"'.$my_string = str_replace(array('®', '™'), array('', ''), mysqli_real_escape_string(htmlspecialchars($r['_source']['ddata']))).'","day":"'.$day.'","month":"'.$month.'","year":"'.$year.'"},';	
		
	}
	$concatsearchres=substr($concatsearchres, 0, -1);
}
$concatsearchres='['.$concatsearchres.']';
# JSON-encode the response
echo $json_response = $concatsearchres;
?>



