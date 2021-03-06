<?php
@session_start();
include("../commonfunctions.php");
use Elasticsearch\ClientBuilder;
require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();
$searchParams['index'] = 'mongoindex1';
$searchParams['type']  = 'u_endata';

$concatstr="";

if(isset($_REQUEST['counter']))
{
	$pavolder = $_REQUEST['counter']*20;
}
else
{
	$pavolder="0";
}

if(isset($_SESSION['ownerid']))
{
	$ownerg = $_SESSION['ownerid'];
	$concatstr .='{ "term" : { "endownerid" : "'.$ownerg.'" } }'; 
}
if(isset($_REQUEST['enduserid']))
{	
	if(($_REQUEST['enduserid']>'0') && ($_REQUEST['enduserid']!="undefined"))
	{
		$enduserid = $_REQUEST['enduserid'];
		$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
	}
}
$concatstr.=', { "range" : { "dtype" : { "gte": 20, "lte": 21 } } }';

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

$searchParams['body']= '{
	"from" : '.$pavolder.',
	 "size" : 20,	
	 "sort" :[ { "ddate" : {"order" : "desc"} } ],
	 "query": { "bool" : { "must" : [ '.$concatstr.'] } } }	         
}';

$searchParams1['body']= '{
	 "from" : '.$pavolder.',
	 "size" : 21,	
	 "sort" :[ { "ddate" : {"order" : "desc"} } ],      
	 "query": {  "bool" : { "must" : [ '.$concatstr.'] } } }
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



	if(isset($_SESSION['ownerid']) || (strlen($_SESSION['ownerid'])>0))
	{
			if(isset($results))
			{
				
				$incrementcnt = $pavolder-19;
				foreach($results as $r)
				{
					$imageDataEncoded = $r['_source']['decdata'];
					$datefind = $r['_source']['ddate'];
					$dtype = $r['_source']['dtype'];
					$sno=$r['_source']['sno'];
					$app_title=$r['_source']['app_title'];
					$enduserid = $r['_source']['enduserid'];
					$queryi = $conn->query("select profilepic,name,designation,dept,groupid from U_endusers where sno='".$r['_source']['enduserid']."'");
					$queryimage = @$queryi->fetch_assoc();
					$string = $app_title;
					if (strlen($string) > 15) {
					
						$stringCut = substr($string, 0, 15);
					
						$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
					}
					else
					{
						$string = $string;
					}
					?>
					 
					 <div class="box">
					  	<div class="boxInner">
					  		<div style="border:1px solid #000;cursor:pointer"  onclick="popupusergallery('popUpDivgallery','<?php echo $imageDataEncoded; ?>','<?php echo $dtype; ?>','<?php echo urlencode($app_title); ?>','<?php echo $sno;?>','<?php echo $enduserid;?>','<?php echo $queryimage['name'];?>','<b><?php echo $queryimage['designation']; ?></b> (<?php echo  ago1($datefind); ?>)')" >
								<img src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=158&h=88"/>
						 	</div>
							<div style="font-size:10px;font-style: italic;font-weight: bold;" title="<?php echo $app_title; ?>">
						 		<?php echo $string; ?>
						 	</div>
						 	<div class="text-center">
						 		<b><?php echo $queryimage['name']; ?></b>
						 	</div>
						 	<div style="text-align:center;font-size:10px;">
							<?php echo ago1($datefind);?>
							</div>
					  	</div>
					</div>
					
					<?php
					$incrementcnt++;
				}
			}
	}
	
$conn->close();
?>

