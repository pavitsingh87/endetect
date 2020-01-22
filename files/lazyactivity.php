<?php 
@session_start();
$title="Lazy Activity | EnDetect";
use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
$client = ClientBuilder::create()->build();
$searchParams['index'] = 'mongoindex1';
$searchParams['type']  = 'u_endata';
$enduserid = base64_decode($_GET["eid"]);
$sd = base64_decode($_GET["sd"])*1000;
$ed = base64_decode($_GET["ed"])*1000;
include("commonfunctions.php");
checkOwnerSession();
include("header.php");
//echo $_SESSION['ownerid'];
?>
<body ng-app="submitExample" id="pavdescription1">
<?php include("headerbar.php");?>
<div style="clear:both"></div>
<div id="content-main" ng-controller="UserProfileController">
	<script type="text/javascript" src="js/ajaxupload.3.5.js?<?php echo time(); ?>"></script>
	<style type="text/css">
		#profile-picture {
			position: relative;
			margin-left: 1px;
		}
		
		#profile-picture-caption {
			line-height: 26px;
			margin-bottom: -1px;
			margin-left: 4px;
			width: 180px;
		}
	</style>
	<div id="pen1">
		<div id="profile-picture"></div>
		<br class="clearfix">
		<?php
			homemenuoptions();
			//useroptions();
			categories();
		?>
		<br> <br>
	</div>
	<div id="pen2">
			<?php
									if($enduserid!="")
									{
										$decdatastr = '	"must" : [ {"term" : { "enduserid" : '.$enduserid.' }}, { "range" : { "ddate" : { "gte" : '.$sd.',  "lte" : '.$ed.' } } } ]';
									}
									$searchParams['body']= '{
											"from" : 0,
											"size" : 100, 
											"sort" : [ { "ddate" : {"order" : "desc"}}, "_score" ], 
											"query" : { 
												"bool" : {
													'.$decdatastr.'		
												} 
											}
									}';
									$retDoc = $client->search($searchParams);
									if($retDoc['hits']['total']>=1)
									{
										$results=$retDoc['hits']['hits'];
									}
									if(isset($results))
									{
										foreach($results as $r)
										{
											$datefind = $r['_source']['ddate'];
											$lastaccess = ago($r['_source']['ddate']);
											$dtype = $r['_source']['dtype'];
											$watchlist = $r['_source']['watchlist'];
											$noteflag = $r['_source']['noteflag'];
											$note	= $r['_source']['note'];
											$apptitle = $r['_source']['app_title'];
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
												
											
											
											$dtypeque = $conn->query("select originaltxt from searchin where id='".$r['_source']['dtype']."'");
											$dtypequery = @$dtypeque->fetch_assoc();
											$queryima = $conn->query("select profilepic,name,designation,dept,groupid from U_endusers where sno='".$r['_source']['enduserid']."'");
											$queryimage = @$queryima->fetch_assoc();
											
											if(($_REQUEST['dtype']=='12') && ($_REQUEST['ddatasearch']!=''))
											{
												$apptitle = str_replace(strtolower($apptitle),"<span class='highlight'>".strtolower($apptitle)." </span>",strtolower($apptitle));
												
												 
											}
											else if(($_REQUEST['dtype']!='12') && ($_REQUEST['ddatasearch']!=''))
											{
												
												$string_to_decrypt = str_replace(strtolower($ddatasearch),"<span class='highlight'>".strtolower($ddatasearch)."</span>",strtolower($string_to_decrypt));
													
											}
											
											$json_user = $string_to_decrypt;
											$vtext = $json_user;
											
											if($dtype=='5')
											{
												$xmpstyle = "";
											}
											else
											{
												$xmpstyle = "word-wrap: normal;white-space: normal;";
											}
											?>
											<div class="divbox" id="endetect<?php echo $r['_source']['sno']; ?>" >
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
														<?php 
														}
														else
														{
														?>
														<li role="presentation" id="addtonotelist<?php echo $r['_source']['sno'];?>"><a role="menuitem" tabindex="-1" onclick="addnote('<?php echo $r['_source']['sno']; ?>','1','<?php echo $r['_source']['note'];?>')">Add Note</a></li>
														<?php 
														} 
														?>
														<li role="presentation"><a role="menuitem" tabindex="-1" onclick="edituser('<?php echo $r['_source']['enduserid'];?>','<?php echo $queryimage['name'];?>','<?php echo $queryimage['dept'];?>','<?php echo $queryimage['designation'];?>','<?php echo $queryimage['groupid'];?>')">Edit User</a></li>
												  	<li role="presentation"><a role="menuitem" tabindex="-1"  onclick="deletefromlist('<?php echo $r['_source']['sno']; ?>')">Delete</a></li>
												  	
													</ul>
												</div>
												<div id="note<?php echo $r['_source']['sno']?>" style="float:right;margin-right: 5px;margin-top: 11px;cursor:pointer;">
													<?php if($noteflag=='1') { ?>
													<span class="glyphicon glyphicon-list-alt"  onclick="shownote('<?php echo $r['_source']['sno']?>')"></span>
													<?php } ?>
												</div>
												<div id="watchlistimg<?php echo $r['_source']['sno']?>" style="float:right;">
												  <?php if($watchlist=='1') { ?>
												  <img src="images/watchlist.png">
												 <?php } ?>
												</div>
												<div id="stbody6" class="stbody">
														<?php if($_REQUEST["enduserid"]=="") { ?> 
														<div class="stimg">
															<div class="divboximage">
																<a href="userprofile.php?enduserid=<?php echo $r['_source']['enduserid'];?>"><img src="<?php echo $queryimage['profilepic'];?>" class="home_profile_pic"></a>
															</div>
														</div>
														<?php } ?>
														<?php 
															if($_REQUEST["enduserid"]!="") { 
																$style="margin-left:2px;";
															}
															if(isset($_REQUEST['enduserid']))
															{

																?>
																	<div class="sttext1" style='<?php echo $style; ?>'>
																<?php   
															}
															else
															{
																?>
																	<div class="sttext" style='<?php echo $style; ?>'>
																<?php 
															}
														?>
																		<a id="6" class="stdelete" title="Delete update" href="#"> </a>
																		
																			<div class="divboxname" style="float:left">
																				<?php if($_REQUEST["enduserid"]=="") { ?> 
																				<b>
																					<a href="userprofile.php?enduserid=<?php echo $r['_source']['enduserid'];?>" style="float:left;">
																						<?php echo $queryimage['name']; ?>
																					</a>
																				</b>
																				<?php } else { ?> <div style="float:left"><?php echo $apptitle;?></div> <?php } ?>
																				<div class="prevnextsearch glyphicon glyphicon-search" onclick="showprevnext('<?php echo $r['_source']['sno']; ?>','<?php echo $r['_source']['enduserid']?>')" id="movefocus<?php echo $r['_source']['sno']; ?>" style="float:left;"></div>
																				&nbsp;&nbsp;&nbsp;
																			</div>
																		<br>
																		<div style="clear:both;"></div>
																		<div class="sttime" style="margin-top:5px;">
																			<div style="float:left;"><?php echo $lastaccess;?></div><div style="float:left;margin-left:10px;"><i style="color: rgb(240, 101, 60);"><?php echo $dtypequery['originaltxt']; ?></i></div>
																		</div>
																		<div style="clear:both;"></div>
																		<?php if($_REQUEST["enduserid"]=="") { ?>
																		<div style="color:#3C5C9E;">
																		<i>
																			<div style="float:left;"><?php echo $apptitle;?></div>
																		</i>
																		</div>	
																		<?php } ?>								
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
																						<div style="margin-top:10px;">
																						<?php if($_REQUEST["enduserid"]!="") { ?>
																							<img style="cursor:pointer;border:1px solid #ccc;padding:3px;" src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=200&h=113"  onclick="popupusergallery('popUpDivgallery','<?php echo $imageDataEncoded; ?>','<?php echo $dtype; ?>','<?php echo mysqli_escape_string($r['_source']['app_title']); ?>','<?php echo $r['_source']['sno']?>','<?php echo $r['_source']['enduserid']; ?>','<?php echo $queryimage['name']; ?>','<?php echo $queryimage['designation'] ?>')"/>
																						<?php
																							} else {
																								?>
																								<img style="cursor:pointer;border:1px solid #ccc;padding:3px;" src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=200&h=113"  onclick="popupgallery('popUpDivgallery','<?php echo $imageDataEncoded; ?>','<?php echo $dtype; ?>','<?php echo mysqli_escape_string($r['_source']['app_title']); ?>','<?php echo $r['_source']['sno']?>','<?php echo $r['_source']['enduserid']; ?>','<?php echo $queryimage['name']; ?>','<?php echo $queryimage['designation'] ?>')"/>
																								<?php
																							}
																						?>
																						</div>
																						<?php
																						} 
																						else 
																						{
																							?>
																							<div id="divexpandbox<?php echo $r['_source']['sno']; ?>"  style="margin-top:10px;" class="<?php echo $decdatastyle;?>"><xmp><?php echo $vtext;?></xmp></div>
																							<?php
																						}							
																					?>
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
																			<?php
																		}
																	}
																	else
																	{
																		echo "<div style='margin: 0px auto;'><center><b> No records found</b></center></div>";
																	}
																?>
											</div>
											<br class="clearfix">
	</div>
</div>
<?php include("footer.php")?>
