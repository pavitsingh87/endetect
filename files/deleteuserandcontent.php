<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<?php
@session_start();


include("services/connection.php");
include("vendor/autoload.php");

$client = new Elasticsearch\Client();
$searchParams['index'] = 'mongoindex';
$searchParams['type']  = 'u_endata';

$concatstr="";
$_REQUEST['enduserid']="534";
if(isset($_REQUEST['enduserid']))
{	
	if($_REQUEST['enduserid']>'0')
	{
		$enduserid = $_REQUEST['enduserid'];
		$concatstr .='{ "term" : { "enduserid" : "'.$enduserid.'" } }';
	}
}

$searchParams['body']= '{
	 "sort" :[
        { "ddate" : {"order" : "desc"}}	],        
	"query": {
		"filtered" : {
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

$retDoc1 = $client->search($searchParams1);

if($retDoc['hits']['total']>=1)
{
	$results=$retDoc['hits']['hits'];
}
if($retDoc1['hits']['total']>=1)
{
	$results1=$retDoc1['hits']['hits'];
}

$mincount = count($results);
$totalcount = count($results1);
$vb=0;

	if(isset($results))
	{
		foreach($results as $r)
		{
			$params = [
				'index' => 'mongoindex',
				'type' => 'u_endata',
				'id' => 'my_id'
			];
			$vb++;
			echo $sno = $vb."-".$r['_source']['_id']."-".$r['_source']['dtype'];
			if($r['_source']['dtype']=='20' || $r['_source']['dtype']=='21')
			{
				if (!unlink($r['_source']['decdata']))
				  {
				  echo ("Error deleting $file");
				  }
				else
				  {
				  echo ("Deleted $file");
				  }
			}
			echo "<br />";
			
		}
	}
		
	mysql_close($db_connect);
?>