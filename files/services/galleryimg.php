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
if(isset($_REQUEST['lastsno']))
{	
	if($_REQUEST['lastsno']>'0')
	{
		$lastsno = $_REQUEST['lastsno'];
		$concatstr .=',{ "range" : {"sno":{"lte":"'.$lastsno.'"} } }';
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


$searchParams['body']= '{
	"from" : '.$pavolder.',
	 "size" : 1,	
	"filter" : {
            "range" : {
                "dtype" : {
                    "gte": 20,
                    "lte": 21
                }
            }
        },
	"query": { "filtered" : { "filter" : { "bool" : { "must" : [ '.$concatstr.'] } } } } }	         
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
				
				$incrementcnt = $pavolder-19;
				foreach($results as $r)
				{
					$imageDataEncoded = $r['_source']['decdata'];
					$dtype = $r['_source']['dtype'];
					$sno=$r['_source']['sno'];
					$app_title=$r['_source']['app_title'];
					$string = html_entity_decode($app_title);
					if (strlen($string) > 30) {
	
						$stringCut = substr($string, 0, 30);
	
						$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
					}
					else
					{
						$string = $string;
					}
					?>
					<div align="center" style="padding:10px;"> 
						<?php echo $string; ?> <img src='images/stretch.png' class='stretchimg' id='stretch' onclick='stretchimage("<?php echo $enduserid?>")'>
					</div>
					<div> 
					<img src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=1129&h=635"  id="<?php echo $enduserid?>" class='galimg'>
					</div>
					
					<?php
					$incrementcnt++;
				}
			}
	}
	
$conn->close();
?>

