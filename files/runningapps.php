<?php
$title = "Running Apps | EnDetect";
include("commonfunctions.php");
if(isset($_GET['eid']))
{
	$enduserid= base64_decode($_GET["eid"]);
}
?>
<?php include("header.php");?>
<body onload="lastrecordid('<?php echo $enduserid;?>');latestimages('<?php echo $enduserid;?>');" ng-app="submitExample">
<?php include("headerbar.php");?>
<script type="text/javascript">

function lastrecordid(str)
{
	document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            document.getElementById("lastrecid").value =xmlhttp.responseText;
            homestreamuserprofile(str);
        }
    }
    xmlhttp.open("POST","services/getlastrecid.php?enduserid="+str,true);
    xmlhttp.send();
}
function newdatacome(str)
{
	var streamtrue = document.getElementById('streamuserres').value;

    if(streamtrue=='1')
    {
		homestreamuserprofile(str);
		setTimeout(function(){ newdatacome(str); }, 3000);
    }

}
function homestreamuserprofile(enduserid)
{

	document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        	document.getElementById('loadinggif').style.display="none";

            document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/userstream.php?enduserid="+enduserid,true);
    xmlhttp.send();

}
function loadmoreuserprofile(enduserid,id)
{
	id.style.display="none";
	document.getElementById('loadinggif').style.display="block";
	var incrementer = document.getElementById('streamuserres').value;
	var addition = parseInt(incrementer)+1;
	document.getElementById('streamuserres').value=addition;
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          	document.getElementById('loadinggif').style.display="none";
          	$('html, body').animate({
          	    scrollTop: $(window).scrollTop() + 500
          	},{
          		duration: 1000});
            document.getElementById("streamuser").innerHTML = document.getElementById("streamuser").innerHTML + xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/userstream.php?streamolder=1&lastrecordid="+addition+"&enduserid="+enduserid,true);
    xmlhttp.send();

}
    </script>
   <input type="hidden" id="lastrecid" name="lastrecid" value="">
   <input type="hidden" id="streamuserres" name="streamuserres" value="0">

<div id="content-main" ng-controller="UserProfileController">

	<div class="row-body" >

		<!-- <div class="cover-container"  id="sidebar-container">
			<div class="cover-content">
				<div class="three columns">
					<div class="sidebar-container" id="sidebar-container">
						<div class="sidebar-content">
							<div class="message-form-header">Statistics</div>

							<div class="sidebar-about">
								Activation Date: <strong ng-bind="userdetp.activationdate"></strong>
							</div>
							<div class="sidebar-about">
								Typed Words: <strong ng-bind="userdetp.typed_words"></strong>
							</div>
							<div class="sidebar-about">
								Copied Words: <strong ng-bind="userdetp.copiedwords"></strong>
							</div>
							<div class="sidebar-about">
								Files Copied: <strong ng-bind="userdetp.files_copies"></strong>
							</div>

						</div>
						<div id="hide-cont" style="display:block;cursor:pointer;float:right;margin-top:-35px;position:absolute;">
							<button class="btn btn-default" type="button" >
							    <span class="glyphicon glyphicon-collapse-up" style="font-size: 14px;"></span>
							     Hide Profile
							</button>
						</div>
					</div>
				</div>
				<div class="seven columns">

					<img  class="cover-avatar" src="thumbnail.php?file={{userdetp.profilepic}}&width=100&height=100&cropratio=1:1" />
					<br style="clear:both;" /><br style="clear:both;" /><br style="clear:both;" /><br style="clear:both;" /><br style="clear:both;" />
					<br style="clear:both;" />
					<div>
					  Vivek Kumar SAHA
					</div>
					<br style="clear:both;" />
					<div>
					  Designation - Department
					</div>
				</div>
			</div>
		</div>

		<div id="show-cont" style="float:right;display:none;cursor:pointer;">
			<button class="btn btn-default" type="button">
    			<span class="glyphicon glyphicon-collapse-down" style="font-size: 14px;"></span>
			     Show Profile
			</button>
		</div>-->
		<div style="clear: both"></div>



		<div id="pdata" style="margin-top: -65px;">

			<div id="resultval">
				<!-- content starts here -->
				<div id="content-main">
					<!-- display global alert messages -->
					<!-- sidebar template -->
					<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
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
						<?php
						userprofile_account($enduserid);
						activityhideshow($enduserid);
						leftbar_account($enduserid);
						categories();
						 ?>
						<br> <br>
						<?php deleteUserModal(); ?>
					</div>
					<div id="pen2">
						<div id="pen2-left">
								<div class="col-md-12">
									<div class="col-md-4">
											<h3>Running Apps</h3>
									</div>
									<div class="col-md-6 text-right" style="float:right;">
											<a href="javascript:void(0)" onclick="runningappsrefresh()" id="lazy-minutes-refresh" class="btn btn-primary btn-xs">Refresh <i class="glyphicon glyphicon-refresh"></i></a>
									</div>
								</div>
								<div class="clearfix"></div>
						 		<hr>
								<br>
								<form class="form-inline" method="POST" action="<?php echo baseurl; ?>runningapps.php?eid=<?php echo $_REQUEST["eid"]; ?>">
									<input type="text" id="runningappsdate"  class="form-control" name="rdate" placeholder="Date" autocomplete="off" value="<?php if(isset($_POST["rdate"])) { echo $_POST["rdate"]; } else { echo Date("d-m-y", time()); } ?>">
									<button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
								</form>
								<br>
								<script type="text/javascript">
									$(document).ready(function () {
										$('#toggle-view .parent').click(function () {
											var text = $(this).next();
											if (text.is(':hidden')) {
												text.slideDown('200');
												$(this).children('span').html('-');
											} else {
												text.slideUp('200');
												$(this).children('span').html('+');
											}
										});

										$('#runningappsdate').datepicker({
											format: "yyyy-mm-dd",
											autoclose: true
						                    //maxDate:'0'
						                });
									});
								</script>
								<?php
									if($_POST["rdate"]=="") {
										$rdate = Date("Y-m-d", time());
									}
									else
									{
										$rdate = Date("Y-m-d", strtotime($_POST["rdate"]));
									}
									$runningproc = $conn->query("select procname, title, sum(activetime) as soat, sum(totaltime) as sott, count(*) as cnt from U_runningapps where enduserid='".$enduserid."' AND datesave='".$rdate."' group by procname");
									if($runningproc->num_rows)
									{
										?>
										<table id="toggle-view" class='table table-striped table-condensed table-bordered table-hover'>
											<tr>
												<td style="width:4%;">
												</td>
												<td style="width:24%;  text-align:left;">
													<b>Application Name</b>
												</td>
												<td style="width:24%; text-align:right;">
													<b>Worked Duration</b>
												</td>
												<td style="width:24%; text-align:right;">
													<b>Duration</b>
												</td>
											</tr>
										<?php
											$r=1;
											while($row = $runningproc->fetch_assoc())
											{
												//$procarr=array("procname" => $row["procname"]);
												if($row["cnt"]>1)
													{
												?>

												<tr class="parent">
												<?php } else { ?><tr> <?php } ?>
													<td style="text-align:center">
														<?php if($row["cnt"]>1) {
															?><span><b>+</b></span><?php
														} ?>
													</td>
													<td style="text-align:left"><?php echo ucfirst($row["procname"]); ?></td>
													<td style="text-align:right"><?php echo converttotime($row["soat"]); ?></td>
													<td style="text-align:right"><?php echo converttotime($row["sott"]); ?></td>
												</tr>
												<?php
													if($row["cnt"]>1)
													{
														?>
														<tr class="panel" style="display:none;">
																<td colspan="4">
														<?php
														$runningproc1 = $conn->query("select procname, title, activetime, totaltime from U_runningapps where enduserid='".$enduserid."' AND datesave='".$rdate."' AND procname='".$row["procname"]."'");
														?>
														<table class="table">
															<tr>
																<td><b>Application Name</b></td>
																<td><b>Title</b></td>
																<td><b>Start Time</b></td>
																<td><b>End Time</b></td>
															</tr>
														<?php
														while($row1 = $runningproc1->fetch_assoc())
														{
															?>


																		<tr>
																			<td>
																				<?php
																					echo $row1["procname"];
																				?>
																			</td>
																			<td>
																				<?php
																					echo $row1["title"];
																				?>
																			</td>
																			<td>
																				<?php
																					echo converttotime($row1["activetime"]);
																				?>
																			</td>
																			<td>
																				<?php
																					echo converttotime($row1["totaltime"]);
																				?>
																			</td>
																		</tr>


															<?php
														}
														?>
														</table>
														</td>
															</tr>
														<?php
													}
													?>
												<?php
												$r++;
											}
											?>
										</table>
										<?php
									}
									else
									{
										?>
										<br>
										<div>No records found</div>
										<?php
									}
								?>
							</div>
							<div class="hide" id="pen3-left" style="float: left;height: auto;margin-left: 13px;width: 98%;">
							</div>
							<br class="clearfix">
					</div>

					<!-- pagination -->
				</div>


			</div>

		</div>
	</div>
</div>
</div>
</div>


<?php include("footer.php")?>
