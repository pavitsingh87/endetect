<?php
$title = "Web History | EnDetect";
include_once("commonfunctions.php");
include_once("header.php");

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
$client = ClientBuilder::create()->build();
$eid=$_REQUEST["eid"];
$enduserid = base64_decode($_REQUEST["eid"]);
$filterrec = $_REQUEST["filterrec"];
$wFromDate = $_REQUEST["wFromDate"];
$wToDate = $_REQUEST["wToDate"];
$searchParams['index'] = 'mongoindex1';
$searchParams['type']  = 'u_endata';
//echo $_POST["filterrec"];

function createsubstring($string)
{
		if (strlen($string) > 50) {

			$string = substr($string, 0, 50);
			$string = $string."...";
			//$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
		}
		else
		{
			$string = $string;
		}
		return $string;
}
if(isset($_REQUEST['pageno']))
{
	$pavolder = $_REQUEST['pageno']*10;
}
else
{
	$pavolder="0";
}
?>
<body ng-app="submitExample" id="pavdescription1">
<?php include_once("headerbar.php");?>

<div style="clear:both"></div>
	<div id="content-main" ng-controller="ExampleController">
		<div id="pen1">
			<div id="profile-picture"></div>
			<br class="clearfix">
			<?php
				if($_REQUEST["eid"]!="")
				{
					userprofile_account($enduserid);
					leftbar_withoutlive_account($enduserid);
					deleteUserModal();
				}
				else
				{
					homemenuoptions();
					//categories();
				}
			?>
			<br /> <br />
		</div>
		<div id="pen2">
			<div id="pen2-left">
				<br />
				<div class="col-md-12" style="padding-left:0px;">
					<div class="col-md-4" style="padding-left:0px;">
							<h3 class="">Web History</h3>
					</div>
					<div class="col-md-6 text-right" style="float:right;">
							<a href="javascript:void(0)" onclick="webrefresh()" id="web-refresh" class="btn btn-primary btn-xs">Refresh <i class="glyphicon glyphicon-refresh"></i></a><!-- &nbsp;<a  id="web-delete" class="btn btn-danger btn-xs">Delete <i class="glyphicon glyphicon-trash"></i></a> -->
					</div>
				</div>
				<div class="clearfix"></div>
				<hr>
				<div id="load-content"></div><br />
				<?php
				$pageurlconcat="";
				if(isset($_SESSION["ownerid"]))
				{
					$ownerstr= ', {"term" : { "endownerid" : '.$_SESSION["ownerid"].' }}';
				}
				if($enduserid!="")
				{
					$pageurlconcat.="eid=".$eid;
					$decdatastr = '	, {"term" : { "enduserid" : '.$enduserid.' }}';
				}
				if($filterrec!="")
				{
					if($pageurlconcat!="")
					{
						$pageurlconcat.="&";
					}
					$pageurlconcat.="filterrec=".$filterrec;
					$rgconcat="";
			   	    $rg = (explode(" ",$filterrec));
			   	    $rgcount = count($rg);
			   	    if($rgcount>1)
			   	    {
				   	    for($i=0;$i<$rgcount;$i++)
				   	    {
				   	    	if($i==($rgcount-1))
				   	    	{
				   	    		$rgconcat.= '{ "wildcard" : { "title" : "*'.$rg[$i].'*" } }, { "wildcard" : { "url" : "*'.$rg[$i].'*" } }';
				   	    	}
				   	    	else
				   	    	{
				   	    		$rgconcat.= '{ "wildcard" : { "title" : "*'.$rg[$i].'*" } }, { "wildcard" : { "url" : "*'.$rg[$i].'*" } } ,';
				   	    	}
				   	    }
			   		}
			   		else
			   		{
			   			$rgconcat = '{ "wildcard" : { "title" : "*'.$filterrec.'*" } }, { "wildcard" : { "url" : "*'.$filterrec.'*" } }';
			   		}
					$decdatastr.= ',  {
											"bool": {
														"should" : [
															'.$rgconcat.'
																]
			   			}
			   		}';
				}
				if($wFromDate!="" && $wToDate!="")
				{
					if($pageurlconcat!="")
					{
						$pageurlconcat.="&";
					}
					$wFromDate = $wFromDate." 00:00:00";
					$wToDate = $wToDate." 23:59:59";
					$pageurlconcat.="wFromDate=".$_GET['wFromDate']."&wToDate=".$_GET['wToDate'];
					$decdatastr.= ',{ "range" : { "visitedtime" : { "gte" : '.strtotime($wFromDate).',  "lte" : '.strtotime($wToDate).' } } }';
				}

				if(isset($_GET['wSort'])) {
					$wSort = $_GET['wSort'];
				} else {
					$wSort = "DESC";
				}

				$searchParams['body']= '{
					"from" : '.$pavolder.',
						"size" : 100,
						"sort" : [ { "ddate" : {"order" : "'. $wSort .'"}}, "_score" ],
						"query" : {
							"bool" : {
								"must" : [ {"term" : { "dtype" : "42" }} '.$ownerstr.$decdatastr.']
							}
						}
				}';
				$retDoc = $client->search($searchParams);
				$page_url = baseurl ."webhistory.php?".$pageurlconcat;
				?>
				<form class="form-inline" method="GET" action="<?php echo $page_url ?>">
					<input type="hidden" name="eid" value="<?php echo @$_GET["eid"] ?>">
						<!-- <input type="hidden" name="eid" id="eid" ng-value="{{customSelected1.id}}">
						<!-- <div class="form-group mx-sm-3 mb-2">
		  					<input name="eid" id="eid" autocomplete="Off" ng-click="openoptions(customSelected1.id)" type="text" placeholder="All" ng-model="customSelected1" typeahead="state as state.name for state in statesWithFlags | filter:$viewValue | limitTo:5" style="border-right: 1px solid #626060;" class="ng-pristine ng-valid">
						</div> -->
		  				<div class="form-group mx-sm-3 mb-2">
		    				<input type="text" class="form-control" id="filterrec" placeholder="Search" name="filterrec" value="<?php if($filterrec!="") { echo $filterrec; } else { echo ""; } ?>">
		  				</div>
		  				<div class="form-group mx-sm-3 mb-2">
							<input placeholder="From Date" type="text" id="wFromDate" class="form-control" name="wFromDate" value="<?php if($_GET['wFromDate']!="") { echo $_GET['wFromDate']; } else { echo ""; } ?>">
						</div>
						<div class="form-group mx-sm-3 mb-2">
							<input placeholder="To Date" type="text" id="wToDate" class="form-control" name="wToDate" value="<?php if($_GET['wToDate']!="") { echo $_GET['wToDate']; } else { echo ""; } ?>">
						</div>
						<div class="form-group mx-sm-3 mb-2">
							<select name="wSort" class="form-control">
								<option value="DESC" <?php if(isset($_GET['wSort']) && $_GET['wSort'] == 'DESC') {echo 'selected';} ?>>DESC</option>
								<option value="ASC" <?php if(isset($_GET['wSort']) && $_GET['wSort'] == 'ASC') {echo 'selected';} ?>>ASC</option>
							</select>
						</div>
						<button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</form>
				<?php
				if($retDoc['hits']['total']>=1)
				{
					$totalrecords = $retDoc['hits']['total'];
					$results = $retDoc['hits']['total'];
					$total_pages = floor($totalrecords/100);
					$results = $retDoc['hits']['hits'];

					?>
					<?php
						if($_POST["rdate"]=="") {
							$rdate = Date("Y-m-d",time());
						}
						else
						{
							$rdate = Date("y-m-d",strtotime($_POST["rdate"]));
						}
					?>

					<table class="table table-striped">
					  <thead>
					    <tr>
					      <!-- <th scope="col">#</th> -->
					      <?php if($enduserid=="") { ?>
					      <th scope="col" style="width:100px">User</th>
					      <?php } ?>
					      <th scope="col">Url</th>
					      <th scope="col">Title</th>
					      <!-- <th scope="col">Profile</th> -->
					      <th scope="col">Visited Time</th>
					      <th scope="col"></th>
					      <!-- <th>Time Saved</th> -->
					    </tr>
					  </thead>


					<?php
					if(@$_REQUEST["pageno"]=="")
					{
							$_REQUEST["pageno"]="1";
					}

					$count = (($_REQUEST["pageno"]*100)-100)+1;
					if(isset($results))
					{
						foreach($results as $r)
						{
							$sno = $r['_source']['sno'];
							$url = urldecode($r['_source']['url']);
							$title = $r['_source']['title'];
							$profile = $r['_source']['profile'];
							$visitedtime = $r['_source']['visitedtime'];
							$browser = $r['_source']['browser'];
	/*						if($browser=="")
							{
								$browser = $r['_source']['browser']['0'];
							}
	*/						if($titlw=="")
							{
								$title = $r['_source']['title'];
							}
							$ddate	= $r['_source']['ddate'];
							$userid = $r['_source']["enduserid"];
							?>
							<tbody><tr>
							<!-- <td scope="row"><?php echo $count; ?></td> -->
							<?php if($enduserid=="") { ?>
					      		<td scope="col"><?php getUserDet($userid); ?></td>
					      	<?php } ?>
							<td><a  style="float:left;" href="<?php echo $url; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $url; ?>"><?php echo createsubstring($url); ?></a>
								<div style="clear:both"></div>
								<!-- <?php if($title!="") { ?><div><b>Title : <?php echo $title; ?></b></div><?php } ?> -->
								<?php if($profile!="") { ?><div>Profile : <?php echo $profile; ?></div><?php } ?>
							</td>
							<td title="<?php echo $title; ?>"><?php echo createsubstring($title); ?></td>
						    <!-- <td><?php echo createsubstring($profile); ?></td> -->
						    <td><?php echo Date("d/m/y H:i:s",$visitedtime); ?></td>
						    <td><div  style="float:left;"><?php if($browser=="Internet Explorer") { ?><img src="images/ie.png"> <?php } else if($browser=="Mozilla Firefox") { ?><img src="images/firefox.png"> <?php } else if($browser=="Google Chrome") { ?><img src="images/chrome.png"> <?php } ?></div></td>
						    <!-- <td><?php echo Date("d-m-Y H:i:s",($ddate/1000)); ?></td> -->
						    </tr></tbody>
						    <?php
						    $count++;
						}
					}
					?>

					</table>
					<div style="align:center">
						<?php
						echo paginate("100", $_REQUEST["pageno"], $totalrecords,$total_pages , $page_url);
						?>
					</div>
					<?php }
					else
					{
						?>
						<div class="col-md-12" style="padding-left:0px;">
							<br>
							No records found
						</div>
						<?php
					} ?>
			</div>
			<br class="clearfix">
		</div>
	</div>
</div>
<?php include("footer.php"); ?>
