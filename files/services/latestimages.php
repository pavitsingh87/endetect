<?php
@session_start();


include("../commonfunctions.php");
# Include the Autoloader (see "Libraries" for install instructions)
use Elasticsearch\ClientBuilder;
require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();
$searchParams['index'] = 'mongoindex1';
$searchParams['type']  = 'u_endata';

$concatstr="";

$pavolder="0";

if(isset($_SESSION['ownerid']))
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

$dtype = "20";
$concatstr .=',{ "term" : { "dtype" : "'.$dtype.'" } }';

$concatstr .=',{ "term" : { "delete" : "1" } }';


$searchParams['body']= '{
	"from" : '.$pavolder.',
	 "size" : 5,	
	 "sort" :[
        { "ddate" : {"order" : "desc"}}	],        
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

$mincount = count($results);

if(isset($_SESSION['ownerid']) || (strlen($_SESSION['ownerid'])>0))
{
		if(isset($results))
		{
			foreach($results as $r)
			{
				?>
					<div class="divboxdata">
					<?php if($dtype=='20') { 
					$imageDataEncoded = $r['_source']['decdata'];	
					$datefind = $r['_source']['ddate'];
					$dtype = $r['_source']['dtype'];
					$apptitle = mysqli_escape_string($r['_source']['app_title']);
					$sno = $r['_source']['sno'];
					$lastaccess = ago($r['_source']['ddate']);
					$enduserid = $r['_source']['enduserid'];
					$watchlist = $r['_source']['watchlist'];
					$queryi = @$conn->query("select profilepic,name,designation,dept,groupid from U_endusers where sno='".$r['_source']['enduserid']."'");
					$queryimage = @$queryi->fetch_assoc();
					
					if($_REQUEST['enduserid']!='')
					{
						?>
						<div style="margin:5px;float:left;" >
						<img style="cursor:pointer;border:1px solid #ccc;padding:3px;" src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=113&h=113"  onclick="popupusergallery('popUpDivgallery','<?php echo $imageDataEncoded; ?>','<?php echo $dtype; ?>','<?php echo $apptitle; ?>','<?php echo $sno;?>','<?php echo $enduserid;?>','<b><?php echo $queryimage['name'];?></b>','<b><?php echo $queryimage['designation'] ?></b> (<?php echo ago1($datefind);?>)')"/>
						<div style="font-size:9px;text-align:center;">
							<?php echo ago1($datefind);?>
						</div>
						<?php 
					}
					else
					{
						?>
						<div style="cursor:pointer;margin:5px;float:left;">
						<img style="cursor:pointer;border:1px solid #ccc;padding:3px;" src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=113&h=113"  onclick="popupgallery('popUpDivgallery','<?php echo $watchlist; ?>','<?php echo $imageDataEncoded; ?>','<?php echo $dtype; ?>','<?php echo $apptitle; ?>','<?php echo $sno;?>','<?php echo $enduserid;?>','<b><?php echo $queryimage['name'];?></b>','<b><?php echo $queryimage['designation'] ?></b> (<?php echo  ago1($datefind);?>)')"/>
						<div style="text-align:center;">
							<?php echo $queryimage['name']?>
						</div>
						<div style="font-size:9px;text-align:center;">
							<?php echo ago1($datefind);?>
						</div>
						</div>						
						<?php 
					}
					?>	
					
					<?php 
					 } ?>
					</div>
				<?php 
				
			}
			
		}
}
?>
<div style="clear:both"></div>
<?php 	

	$conn->close();
?>

