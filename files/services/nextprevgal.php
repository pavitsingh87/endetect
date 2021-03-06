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

if(isset($_REQUEST['nextrec']))
{
	$pavolder = $_REQUEST['nextrec'];
}
if(trim($_REQUEST['enduserid']) !='')
{
	$enduserid = $_REQUEST['enduserid'];
	$concatstr.= ' , { "term" : { "enduserid" : "'.$enduserid.'" } } ';
}
if(isset($_SESSION['ownerid']))
{
	$ownerg = $_SESSION['ownerid'];
	
}

if(isset($_REQUEST['sno']))
{
	if($_REQUEST['sno']>'0')
	{
		$sno = $_REQUEST['sno'];
	}
}
function encryptRJ256($string_to_encrypt)
{
	$key = "fPixAxOcDiGZFXeCCOfUF3SyGWCQQtuni/cx/yVvgCU="; //INSERT THE KEY GENERATED BY THE C# CLASS HERE
	$iv = "n8OspIyuqBHdTn0q/JRJfi9A9WZV61B8BqJQKxEw64E="; //INSERT THE IV GENERATED BY THE C# CLASS HERE
	$rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_encrypt, MCRYPT_MODE_CBC, $iv);
	$rtn = base64_encode($rtn);
	return($rtn);
}

function decryptRJ256($encrypted)
{
	$key = "fPixAxOcDiGZFXeCCOfUF3SyGWCQQtuni/cx/yVvgCU="; //INSERT THE KEY GENERATED BY THE C# CLASS HERE
	$iv = "n8OspIyuqBHdTn0q/JRJfi9A9WZV61B8BqJQKxEw64E="; //INSERT THE IV GENERATED BY THE C# CLASS HERE
	//PHP strips "+" and replaces with " ", but we need "+" so add it back in...
	$encrypted = str_replace(' ', '+', $encrypted);

	//get all the bits
	$key = base64_decode($key);
	$iv = base64_decode($iv);
	$encrypted = base64_decode($encrypted);

	$rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
	$rtn = unpad($rtn);
	return($rtn);
}

//removes PKCS7 padding
function unpad($value)
{
	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
	$packing = ord($value[strlen($value) - 1]);
	if($packing && $packing < $blockSize)
	{
		for($P = strlen($value) - 1; $P >= strlen($value) - $packing; $P--)
		{
		if(ord($value{$P}) != $packing)
		{
		$packing = 0;
		}
		}
		}

			return substr($value, 0, strlen($value) - $packing);
}

/* For finding the previous image ie one smaller record */

if($_REQUEST['prevnext']=='0')
{
	 $searchParams['body'] = ' {
		"from" : 0,
		"size" : 1,
		"sort" :[
					{ "ddate" : {"order" : "desc"}}	
				],
		"query": {
			"bool" : {
				"filter" : {
					"bool" : {
						"must" : [
							{ "term": { "endownerid" : "'.$ownerg.'" } },{ "term": { "dtype" : "20" } } '.$concatstr.' , { "range" : { "ddate" : { "lt" : "'.$sno.'" } } }
						]
					}
				}
			}
		}
	} ' ;
}
/* for finding the next image ie one greater record  */
if($_REQUEST['prevnext']=='1')
{
	$searchParams['body'] = ' {
		"from" : 1,
		"size" : 1,
		"sort" :[
					{ "ddate" : {"order" : "asc"}}	
				],
		"query": {
			"bool" : {
			"filter" : {
				"bool" : {
				"must" : [
				{ "term": { "endownerid" : "'.$ownerg.'" } },{ "term": { "dtype" : "20" } } '.$concatstr.' , { "range" : { "ddate" : { "gt" : "'.$sno.'" } } }
		]
		}
		}
		}
		}
	} ';
}

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
			$imageDataEncoded = $r['_source']['decdata'];
			$dtype = $r['_source']['dtype'];
			$nsno=$r['_source']['sno'];
			$enduserid = $r['_source']['enduserid'];
			$app_title= $r['_source']['app_title'];
			$string = $app_title;
			$lastaccess = ago($r['_source']['ddate']);
			$queryi = $conn->query("select profilepic,name,designation,dept,groupid from U_endusers where sno='".$enduserid."'");
			$queryimage = @$queryi->fetch_assoc();
			
			if (strlen($string) > 30) {

				$stringCut = substr($string, 0, 30);

				$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
			}
			else
			{
				$string = $string;
			}
			?>

	
	<input type="hidden" id="usergallerycurrentsno"
		name="usergallerycurrentsno" value="<?php echo $nsno;?>"> 
		<img src='images/close.png'  class='strclose' id='close' onclick='closepopupgallery()' style="display:none;">
		

<div class="image">
	
	<img src='thumbnail.php?src=<?php echo $imageDataEncoded?>&w=1129&h=635' id='<?php echo $enduserid; ?>' class='galimg' style='border: 1px solid #000;'>
</div>
<div class="divpopupname">
	<div class='col-md-12'>

        <div class='col-md-9'><b><?php echo $queryimage['name']; if($queryimage['designation']!='') { echo ” - “.$queryimage['designation']; } ?></b> (<?php echo $lastaccess;?>)<br /><?php echo $app_title;?></div>

        <div class='col-md-2 text-right'>

            <a href='javascript:void(0)' id='watchlistimage<?php echo $nsno; ?>' style='top:0;position:unset;left:0px;'>

                <?php if($watchlistflag==“1”) { ?> <img onclick='imagewatchlist(<?php echo $fsno; ?>,0)' src='images/watchlistgreen.png' style='height:20px;'><?php } else { ?><img onclick='imagewatchlist(<?php echo $fsno; ?>,1)' src='images/watchlist.png' style='height:20px;'> <?php } ?>

            </a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist(<?php echo $fsno; ?>)' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a>

        </div>

    </div>
</div>
<div id='counterprevnext' style='display: block;'>
	<input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> 
				<?php if($_REQUEST['enduserid']=='') { ?>
				<img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter'
		onclick='prevgalfooter("<?php echo $nsno; ?>")'> <img
		src='images/sprite_next.png' class='rightimgfooter' id='nextfooter'
		onclick='nextgalfooter("<?php echo $nsno;?>")'>
				<?php } else { ?>
				<img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter'
		onclick='prevgaluser("<?php echo $nsno; ?>","<?php echo $_REQUEST['enduserid']; ?>")'>
	<img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter'
		onclick='nextgaluser("<?php echo $nsno;?>","<?php echo $_REQUEST['enduserid']; ?>")'>
				<?php } ?>
			</div>



<?php
				break;	
				}
			}
	}
	
$conn->close();
?>