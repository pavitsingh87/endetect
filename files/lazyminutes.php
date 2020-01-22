<?php 
$title="Lazy Minutes | EnDetect";
include("commonfunctions.php");
if(isset($_GET['eid']))
{	
	$enduserid= base64_decode($_GET["eid"]);
	$_REQUEST['enduserid']=$enduserid;
}
?>
<?php include("header.php");?>
<body ng-app="submitExample">	
<?php include("headerbar.php");?>
	<div id="content-main" ng-controller="UserProfileController">
		<div class="row-body" >
			<div id="pdata" style="margin-top: -65px;">
				<div id="resultval">
					<div id="content-main">
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
							leftbar_withoutlive_account($enduserid); 
							deleteUserModal(); ?>
							<br> <br>
						</div>
						<div id="pen2">
							<div id="pen2-left">
								<div class="col-md-12">
									<div class="col-md-4">
											<h3>Lazy Minutes</h3>
									</div>
									<div class="col-md-6 text-right" style="float:right;">
											<a href="javascript:void(0)" onclick="lazyminutesrefresh()" id="lazy-minutes-refresh" class="btn btn-primary btn-xs">Refresh <i class="glyphicon glyphicon-refresh"></i></a>
									</div>
								</div>
						 		<div class="clearfix"></div>
						 		<hr>
								<br>
								<?php 
									if($_POST["rdate"]=="") { 
										$rdate = Date("Y-m-d",time());
									}
									else
									{
										$rdate = Date("y-m-d",strtotime($_POST["rdate"]));
									}
								?>
								<script type="text/javascript">
									$(document).ready(function () {
										$('#runningappsdate').datepicker({
						                    dateFormat: "dd-mm-yy",
						                    maxDate:'0'
						                });  
									});
								</script>
								<form class="form-inline" method="POST" action="<?php echo baseurl; ?>lazyminutes.php?eid=<?php echo $_REQUEST["eid"]; ?>">
									<input type="text" id="runningappsdate"  class="form-control" name="rdate" placeholder="Date" value="<?php if($_POST["rdate"]!="") { echo $_POST["rdate"]; } else { echo Date("d-m-Y", time()); } ?>">
									<button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
								</form>
								<br><br>
								<table>
									<tr>
										<td style="width:20%;"><b>Start Date</b></td>
										<td style="width:20%;"><b>End Date</b></td>
										<td style="width:20%;"><b>Lazy Minutes</b></td>
										<td style="width:20%;"><b>View Activity</b></td>
									</tr>
									<?php
										$lazyminutes = $conn->query("select * from U_lazyminutes where enduserid='".$enduserid."' AND datesave='".$rdate."' AND lazymin>0 order by id desc");
										if(($lazyminutes->num_rows)>0)
										{
											while($row = $lazyminutes->fetch_assoc())
											{
												$startdate = base64_encode(strtotime($row["startdatetime"]));
												$enddate = base64_encode(strtotime($row["enddatetime"]));
												$createurl = "lazyactivity.php?eid=".$_GET["eid"]."&sd=".$startdate."&ed=".$enddate;
												?>
												<tr>
													<td><?php echo Date("d-M-Y H:i:s",strtotime($row["startdatetime"])); ?></td>
													<td><?php echo Date("d-M-Y H:i:s",strtotime($row["enddatetime"])); ?></td>
													<td><?php echo $row["lazymin"]; ?></td>
													<td><a href="<?php echo $createurl; ?>" target="_blank">View</a></div></td>
												</tr>
												<?php
											}
										}
									?>	
								</table>
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