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


if(isset($_REQUEST['streamolder']))
{
	$pavolder = $_REQUEST['lastrecordid']*40;
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
	if($_REQUEST['enduserid']>'0')
	{
		$enduserid = $_REQUEST['enduserid'];
		$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
	}
}
if(isset($_REQUEST['dtype']))
{
	if(($_REQUEST['dtype']!='') || (strlen($_REQUEST['dtype'])>0))
	{
		if($_REQUEST['dtype']!='11')
		{
		$dtype = $_REQUEST['dtype'];
		$concatstr .=',{ "term" : { "dtype" : "'.$dtype.'" } }';
		}
	}
}

$concatstr .=',{ "term" : { "delete" : "1" } },{ "term" : { "watchlist" : "1" } }';

if(isset($_REQUEST['ddatasearch']))
{
	if($_REQUEST['dtype']!='')
	{
		$checkdbquery = $conn->query("select * from searchin where id='".$_REQUEST['dtype']."'");
		$fetcharraydb = $checkdbquery->fetch_assoc();
		
		$ddataarray = explode($fetcharraydb['searchtxt'],$_REQUEST['ddatasearch']);
	}
	$ddatasearch = trim($ddataarray[0]);
	$decdatastr ='"query": { "query_string": { "default_operator" : "AND", "analyze_wildcard": true, 	"query" : "*'.$ddatasearch.'*" } },';
}

$searchParams['body']= '{
	"from" : '.$pavolder.',
	 "size" : 40,	
	"query": {
		"bool" : {
        		
              				'.$decdatastr.'
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

$searchParams1['body']= '{
	 "from" : '.$pavolder.',
	 "size" : 41,
	"query": {
		"bool" : {

              				'.$decdatastr.'
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

if(isset($_SESSION['ownerid']) || (strlen($_SESSION['ownerid'])>0))
{
		if(isset($results))
		{
			foreach($results as $r)
			{
				$sno = $r['_source']['sno'];
				$datefind = $r['_source']['ddate'];
				$lastaccess = ago($r['_source']['ddate']);
				$dtype = $r['_source']['dtype'];
				$watchlist = $r['_source']['watchlist'];
				$noteflag = $r['_source']['noteflag'];
				$note	= $r['_source']['note'];
				$apptitle = $r['_source']['app_title'];
				$enduserid = $r['_source']['enduserid'];
				
				if($lastaccess=='showdate')
				{
					$lastaccess=date("M d Y",$datefind)." at ".date("h:i",$datefind);
				}
				$day = date("d",$datefind);
				$month = date("M",$datefind);
				$year = date("Y",$datefind);
				
				$string_to_decrypt=$r['_source']['decdata'];
				
				$decdatalen =  strlen($r['_source']['decdata']);
				if($decdatalen>750)
				{	
					$decdatastyle = "closemoreclass";
				}
					
				$json_user = $string_to_decrypt;
				$vtext = $json_user;
				$dtypequ = $conn->query("select originaltxt from searchin where id='".$r['_source']['dtype']."'");
				$dtypequery = @$dtypequ->fetch_assoc();
				$queryima = $conn->query("select profilepic,name,designation,dept,groupid from U_endusers where sno='".$r['_source']['enduserid']."'");
				$queryimage = @$queryima->fetch_assoc();
				
				if($dtype=='5')
				{
					$xmpstyle = "";
				}
				else
				{
					$xmpstyle = "word-wrap: normal;white-space: normal;";
				}
				?>
					<div class="divbox" id="endetect<?php echo $r['_source']['sno']; ?>">
					<!-- Dropdown start -->
					
					<div class="dropdown" style="float:right;">
				       <span class="caret glyphicon glyphicon-triangle-bottom" id="menu<?php echo $r['_source']['sno']; ?>" data-toggle="dropdown"></span>
				       <ul class="dropdown-menu" role="menu" aria-labelledby="menu<?php echo $r['_source']['sno']; ?>">
				       		<?php if($watchlist=='1') { ?>
				       		<li role="presentation" id="addtowatchlist<?php echo $r['_source']['sno'];?>"><a role="menuitem" tabindex="-1"  onclick="watchlist('<?php echo $r['_source']['sno']; ?>','0')">Remove from watchlist</a></li>
				       		<?php }
				       		else
				       		{
				       		?>
				       		<li role="presentation" id="addtowatchlist<?php echo $r['_source']['sno'];?>"><a role="menuitem" tabindex="-1" onclick="watchlist('<?php echo $r['_source']['sno'];?>','1')">Add to watchlist</a></li>
				       		<?php } ?>
				       		<?php if($noteflag=='1') { ?>
				       		<li role="presentation" id="addtonotelist<?php echo $r['_source']['sno'];?>"><a role="menuitem" tabindex="-1"  onclick="addnote('<?php echo $r['_source']['sno']; ?>','0','<?php echo $note;?>')">Remove Note</a></li>
				       		<?php }
				       		else
				       		{
				       		?>
				       		<li role="presentation" id="addtonotelist<?php echo $r['_source']['sno'];?>"><a role="menuitem" tabindex="-1" onclick="addnote('<?php echo $r['_source']['sno']; ?>','1','<?php echo $r['_source']['note'];?>')">Add Note</a></li>
				       		<?php } ?>
				       		<li role="presentation" id="addtonotelist<?php echo $r['_source']['sno'];?>"><a role="menuitem" tabindex="-1" onclick="showprevnext('<?php echo $r['_source']['sno']; ?>','<?php echo $r['_source']['enduserid']?>')">Show Prev Next</a></li>
				       		<li role="presentation"><a role="menuitem" tabindex="-1" onclick="edituser('<?php echo $r['_source']['enduserid'];?>','<?php echo $queryimage['name'];?>','<?php echo $queryimage['dept'];?>','<?php echo $queryimage['designation'];?>','<?php echo $queryimage['groupid'];?>')">Edit User</a></li>
				          	<li role="presentation"><a role="menuitem" tabindex="-1"  onclick="deletefromlist('<?php echo $r['_source']['sno']; ?>')">Delete</a></li>
				          	
				        </ul>
				      </div>
				      
				      <div id="note<?php echo $r['_source']['sno']?>" style="float:right;margin-right: 5px;margin-top: -2px;cursor:pointer;">
						<?php if($noteflag=='1') { ?>
						<span class="glyphicon glyphicon-list-alt"  onclick="shownote('<?php echo $r['_source']['sno']?>')"></span>
						<?php } ?>
					  </div>
					  
				     <!-- dropdown end -->
				     <!-- watch list start -->
				      <div id="watchlistimg<?php echo $r['_source']['sno']?>" style="float:right;">
				      <?php if($watchlist=='1') { ?><img src="images/watchlist.png">
				     <?php } ?>
				     </div>
				     <!-- watch list end -->
				     <!-- post body start -->
						<div id="stbody6" class="stbody">
							<!-- post img start -->
							<div class="stimg">
								<div class="divboximage">
									<a href="userprofile.php?enduserid=<?php echo $r['_source']['enduserid'];?>"><img src="<?php echo $queryimage['profilepic'];?>" class="home_profile_pic"></a>
								</div>
							</div>
							<!-- post img end -->
							<?php if(isset($_REQUEST['enduserid']))
							{
								?>
									<div class="sttext1">
								<?php   
							}
							else
							{
								?>
									<div class="sttext">
								<?php 
							}
							?>
							
								<a id="6" class="stdelete" title="Delete update" href="#"> </a>
								<b>
									<div class="divboxname" style="float:left">
										<a href="userprofile.php?enduserid=<?php echo $r['_source']['enduserid'];?>">
											<?php echo $queryimage['name']; ?>
										</a>
										&nbsp;&nbsp;&nbsp;
									</div>
									
								</b> <br>
								<div style="clear:both;"></div>
								<div class="sttime">
									<div style="float:left;"><?php echo $lastaccess;?></div><div style="float:left;margin-left:10px;"><i style="color: rgb(240, 101, 60);"><?php echo $dtypequery['originaltxt']; ?></i></div>
								</div>
								<div style="clear:both;"></div>
								<div style="color:#3C5C9E;">
								<i>
									<div style="float:left;"><?php echo $apptitle;?></div>
								</i>
							</div>
							
								<div style="clear:both;"></div>
								<div id="shownoteheader<?php echo $r['_source']['sno']?>" class="note yellow pin" style="display:none;margin-top:-50px;">
									<span class="glyphicon glyphicon-remove" style="float:right;color:#757575;margin-top:-10px;padding: 2px;cursor:pointer;" onclick="closenote('<?php echo $r['_source']['sno']?>')"></span> &nbsp;<span class="glyphicon glyphicon-pencil" style="float:right;color:#757575;margin-top:-10px;padding: 2px;cursor:pointer;" onclick="addnote('<?php echo $r['_source']['sno']?>','1','<?php echo $r['_source']['note']?>')"></span>
									<input type="hidden" name="editnote<?php echo $r['_source']['sno']?>" id="editnote<?php echo $r['_source']['sno']?>" value="<?php echo $r['_source']['note']?>">
									<div id="shownote<?php echo $r['_source']['sno']?>" style="float:left;">
									<?php 
							   			if($noteflag=='1')
							   			{	
							   				echo $r['_source']['note'];
										}
									?>
									</div>
								</div>
								<div id="stexpandbox<?php echo $r['_source']['sno'];?>" >
									<div id="stexpand6">
										<div class="divboxdata">
										<?php if(($dtype=='20') || ($dtype=='21')) { 
										$imageDataEncoded = $r['_source']['decdata'];								
										?>
										<div style="cursor:pointer;margin-top:10px;">
										<img src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=200&h=113"  onclick="popupgallery('popUpDivgallery','<?php echo baseurl.$imageDataEncoded; ?>','<?php echo $dtype; ?>','<?php echo $apptitle; ?>','<?php echo $sno;?>','<?php echo $enduserid;?>','<?php echo $queryimage['name'];?>')"/></div>
										<?php 
										 } else { ?>
										<div id="divexpandbox<?php echo $r['_source']['sno']; ?>"  style="margin-top:10px;" class="<?php echo $decdatastyle;?>"><listing><xmp style="<?php echo $xmpstyle; ?>"><?php echo $vtext;?></xmp></listing></div>
										<?php } ?>
										</div>
										
									</div>
									<?php if($decdatalen>750)
									{
									?>
									<div style="float:right;color: #3B5998;cursor: pointer;text-decoration: none;" id="readmorediv<?php echo $r['_source']['sno'];?>" onclick="readmorediv('divexpandbox<?php echo $r['_source']['sno'];?>','readmorediv<?php echo $r['_source']['sno'];?>','closemorediv<?php echo $r['_source']['sno'];?>')">See More</div><div style="display:none" id="closemorediv<?php echo $r['_source']['sno'];?>" onclick="closereadmore('divexpandbox<?php echo $r['_source']['sno'];?>','readmorediv<?php echo $r['_source']['sno'];?>','closemorediv<?php echo $r['_source']['sno'];?>')">Close</div>
									<?php 
									} 
									?>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				
				<?php 
			}
		}
		if($totalcount>20)
		{
		?>
		
		<div class="panel-heading" onclick="loadmorewatchlist(this)" id="loadmore<?php echo $r['_source']['sno'] ?>">
			<a ng-hide="ascrec">
				<h4 class="panel-title">
					<center style="cursor: pointer;">.. load more ..</center>
				</h4>
			</a>
		</div>
		<?php 
		}	
		if($mincount=='0')
		{
			?>
		<div class="panel-heading">
			<a ng-hide="ascrec">
				<h4 class="panel-title">
					<center style="cursor: pointer;">No Records Found</center>
				</h4>
			</a>
		</div>
		<?php 
		}	
}	
else {

	echo "login";
}
	$conn->close();
?>

