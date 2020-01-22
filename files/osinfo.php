<?php 
include("connection.php");
include("commonfunctions.php");
$title = "OSINFO | EnDetect";
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
			<div id="pdata" style="margin-top:-65px;">
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
								<div class="col-md-10">
									<div class="col-md-4">
											<h3>OS Info</h3>
									</div>
									<div class="col-md-6 text-right" style="float:right;">
											<a href="javascript:void(0)" onclick="osinforefresh()" id="os-info-refresh" class="btn btn-primary btn-xs">Refresh <i class="glyphicon glyphicon-refresh"></i></a>
									</div>
								</div>
						 		<div class="clearfix"></div>
						 		<hr>
						 		<style>
								#pen2-left .col-md-12 div
								{
									margin:10px;
								}
								</style>
									<?php 
										$osinfo_query = $conn->query("select * from U_userpcinfo where enduserid='".$_REQUEST['enduserid']."'");
										if(($osinfo_query->num_rows)>0)
										{
											$row = $osinfo_query->fetch_assoc();
											?>
											<div class="col-md-12">
												<div class="col-md-2">
													<b>OS</b>
												</div>
												<div class="col-md-6">
													<?php echo urldecode($row["os"]); ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>OS Arch</b>
												</div>
												<div class="col-md-6">
													<?php echo urldecode($row["osarch"]); ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>OS Version</b>
												</div>
												<div class="col-md-6">
													<?php echo urldecode($row["osversion"]); ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>CPU</b>
												</div>
												<div class="col-md-6">
													<?php echo $row["cpu"]; ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>RAM</b>
												</div>
												<div class="col-md-6">
													<?php echo $row["ram"]; ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>Machine name</b>
												</div>
												<div class="col-md-6">
													<?php echo $row["cname"]; ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>Workgroup</b>
												</div>
												<div class="col-md-6">
													<?php echo $row["workgroup"]; ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>Hard Disk Drives</b>
												</div>
												<div class="col-md-6">
													<?php echo urldecode($row["hdd"]); ?>
												</div>
												<div class="clearfix"></div>
												<hr>
												<div class="clearfix"></div>
												<div class="col-md-2">
													<b>Network Adapter</b>
												</div>
												<div class="col-md-6">
													<?php echo urldecode($row["networks"]); ?>
												</div>
												<div class="clearfix"></div>
											</div>
											<?php
										}
										else
										{
											?>
											<div class="col-md-12">
												No records found
											</div>
											<?php 
										}
									?>
								<br>
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