<?php
@session_start();
include("../commonfunctions.php");
$page = $_GET["p"];
$start = $page * 10;
$sqlquery = $conn->query("select * from U_getaction where ownerid='".$_SESSION["ownerid"]."' AND actionpath!='' order by sno desc limit $start,10");
$sqlnum = $sqlquery->num_rows;
if($sqlnum>0)
{
	$r=0;
	while($sqlrow = $sqlquery->fetch_assoc())
	{
		$r++;
		$sno = $sqlrow['sno'];
		$dtype = $sqlrow['action'];
		$feedid = $sqlrow['feedid'];
		//$enduserid = $sqlrow['watchlist'];
		//$noteflag = $r['_source']['noteflag'];
		//$note	= $r['_source']['note'];
		$actiondate = strtotime($sqlrow['actiondate']);
		$apptitle = mysqli_escape_string($sqlrow['apptitle']);
		$enduserid =  $sqlrow['enduserid'];
		$endusernamequery = $conn->query("select * from U_endusers where sno='".$enduserid."'");
		$endusername = $endusernamequery->fetch_assoc();

		$imageDataEncoded = $sqlrow["actionpath"];
		?>
		<div class="divbox" id="endetect<?php echo $feedid; ?>">
					<!-- Dropdown start -->
					
					<div class="dropdown" style="float:right;padding:5px;">
				       <span class="caret glyphicon glyphicon-triangle-bottom" id="menu<?php echo $feedid; ?>" data-toggle="dropdown"></span>
				       <ul class="dropdown-menu" role="menu" aria-labelledby="menu<?php echo $feedid; ?>">
				       						       		<li role="presentation" id="addtowatchlist<?php echo $feedid; ?>"><a role="menuitem" tabindex="-1" onclick="watchlist('<?php echo $feedid; ?>','1')">Add to watchlist</a></li>
				       						       						       		<li role="presentation" id="addtonotelist<?php echo $feedid; ?>"><a role="menuitem" tabindex="-1" onclick="addnote('<?php echo $feedid; ?>','1','')">Add Note</a></li>
				       						       		
				       		<li role="presentation"><a role="menuitem" tabindex="-1" onclick="deletefromlist('<?php echo $feedid; ?>')">Delete</a></li>
				          	
				        </ul>
				      </div>
				      
				      <div id="note<?php echo $feedid; ?>" style="float:right;margin-right: 5px;margin-top: 11px;cursor:pointer;">
											  </div>
					  
				     <!-- dropdown end -->
				     <!-- watch list start -->
				      <div id="watchlistimg<?php echo $feedid; ?>" style="float:right;">
				      				     </div>
				     <!-- watch list end -->
				     <!-- post body start -->
						<div id="stbody6" class="stbody">
							<!-- post img start -->
							<div class="stimg">
								<div class="divboximage">
									<a href="userprofile.php?enduserid=<?php echo $enduserid;?>"><img src="uploads/default.jpg" class="home_profile_pic"></a>
								</div>
							</div>
							<!-- post img end -->
																<div class="sttext">
															
								<a id="6" class="stdelete" title="Delete update" href="#"> </a>
								<b>
									<div class="divboxname" style="float:left">
										<a href="userprofile.php?enduserid=<?php echo $enduserid;?>" style="float:left;">
											<?php echo $endusername["name"]; ?>										</a>
										<div class="prevnextsearch glyphicon glyphicon-search" onclick="showprevnext('<?php echo $feedid; ?>','<?php echo $enduserid;?>')" id="mvfs<?php echo $feedid; ?>"></div>
										&nbsp;&nbsp;&nbsp;
									</div>
									
								</b> <br>
								<div style="clear:both;"></div>
								<div class="sttime">
									<div style="float:left;"><?php echo ago($actiondate); ?></div><div style="float:left;margin-left:10px;"><i style="color: rgb(240, 101, 60);"></i></div>
								</div>
								<div style="clear:both;"></div>
								<div style="color:#3C5C9E;">
								<i>
									<div style="float:left;"><?php echo $apptitle; ?></div>
								</i>
							</div>
							
								<div style="clear:both;"></div>
								<div id="shownoteheader<?php echo $feedid; ?>" class="note yellow pin" style="display:none;margin-top:-50px;">
									<span class="glyphicon glyphicon-remove" style="float:right;color:#757575;margin-top:-10px;padding: 2px;cursor:pointer;" onclick="closenote('<?php echo $feedid; ?>')"></span> &nbsp;<span class="glyphicon glyphicon-pencil" style="float:right;color:#757575;margin-top:-10px;padding: 2px;cursor:pointer;" onclick="addnote('<?php echo $feedid; ?>','1','')"></span>
									<input type="hidden" name="editnote<?php echo $feedid; ?>" id="editnote<?php echo $feedid; ?>" value="">
									<div id="shownote<?php echo $feedid; ?>" style="float:left;">
																		</div>
								</div>
								
								<div id="stexpandbox<?php echo $feedid; ?>">
									
									<div id="stexpand6">
										<div class="divboxdata">
																				<div style="margin-top:10px;">
										<img style="cursor:pointer;border:1px solid #ccc;padding:3px;" src="thumbnail.php?src=<?php echo $imageDataEncoded; ?>&w=200&h=113" onclick="popupgallery('popUpDivgallery','','<?php echo $imageDataEncoded; ?>','20','<?php echo $apptitle; ?>','<?php echo $feedid; ?>','<?php echo $enduserid; ?>','<b><?php echo $endusername["name"]; ?></b>','<b></b> (<?php echo ago($actiondate); ?>)')">
											

									</div>
																				</div>
										
									</div>
																		
								</div>								
							</div>
							
						</div>
					</div>
		<?php
		if($r>9)
		{
			?>
			
			<?php
		}
	}
}
?>

				
<?php				
	$conn->close();
?>

