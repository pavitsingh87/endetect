<?php
include_once("commonfunctions.php");
$title="Time Sheet | EnDetect";
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
					<div id="content-main" >
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
							if($enduserid!="")
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

							<br> <br>
						</div>
						<?php if($enduserid!="") { ?>
						<div id="pen2" style="border:0;">
							<div id="pen2-left">
								<div class="col-md-12">
									<div class="col-md-4">
											<h3>Time Sheet</h3>
									</div>
									<div class="col-md-6 text-right" style="float:right;">
											<a href="javascript:void(0)" onclick="timesheetrefresh()" id="lazy-minutes-refresh" class="btn btn-primary btn-xs">Refresh <i class="glyphicon glyphicon-refresh"></i></a>
									</div>
								</div>
						 		<div class="clearfix"></div>
						 		<hr>
								<br>
								<form class="form-inline" method="GET" action="<?php echo baseurl; ?>timesheet.php">
									<input type="hidden" name="eid" id="eid" value="<?php echo $_REQUEST["eid"]; ?>">
									<input type="text" id="runningappsdate"  class="form-control" name="selectedmonth" placeholder="Choose month" value="<?php if($_GET["selectedmonth"]!="") { echo $_GET["selectedmonth"]; } ?>">
									<button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
								</form>
								<br><br>
								<script type="text/javascript">
									$(document).ready(function () {
										$('#toggle-view li').click(function () {
											var text = $(this).children('div.panel');
											if (text.is(':hidden')) {
												text.slideDown('200');
												$(this).children('span').html('-');
											} else {
												text.slideUp('200');
												$(this).children('span').html('+');
											}
										});
										$('#runningappsdate').datepicker({
						                        format: "M-yyyy",
											    minViewMode: 1,
											    maxViewMode: 1,
											    autoclose: true
						                });
									});
								</script>
								<?php
									if($_GET["selectedmonth"]!='')
									{
										$explodemonth = explode("-",$_GET["selectedmonth"]);
										$starttime=Date("Y-m-d H:i:s", strtotime($explodemonth["1"]."-".$explodemonth["0"]."-01 00:00:00"));
										$endtime=Date("Y-m-d H:i:s", strtotime($explodemonth["1"]."-".$explodemonth["0"]."-31 00:00:00"));
										$concatstr.= " AND (starttime between '".$starttime."' AND '".$endtime."')";
									}
									else{
										$starttime = Date("Y-m-01 00:00:00",time());
										$endtime = Date("Y-m-31 23:59:59",time());
										$concatstr.= " AND (starttime between '".$starttime."' AND '".$endtime."')";
									}

									//$runningproc1 = $conn->query("select * from U_useratt as uatt INNER JOIN U_endusers as uend ON uatt.enduserid=uend.sno where uend.ownerid='".$_SESSION["ownerid"]."' ".$concatstr." order by id desc");
									$runningproc1 = $conn->query("SELECT * FROM U_useratt T1, U_endusers T2
										WHERE T1.enduserid = T2.sno AND sno = $enduserid AND ownerid = '". $_SESSION["ownerid"] ."'
										". $concatstr ." ORDER BY id DESC");

									$totalrecords = $runningproc1->num_rows;
									$total_pages = ceil($totalrecords/100);
									if(@$_REQUEST["pageno"]=="")
									{
											$_REQUEST["pageno"]="1";
											$pager=0;
									}
									else
									{
										$pager = (($_REQUEST["pageno"]*100)-100);
									}
									$count = (($_REQUEST["pageno"]*100)-100);

									//$runningproc = $conn->query("select * from U_useratt as uatt INNER JOIN U_endusers as uend ON uatt.enduserid=uend.sno where uend.ownerid='".$_SESSION["ownerid"]."' ".$concatstr."order by id desc limit $pager,100");
									$runningproc = $conn->query("SELECT * FROM U_useratt T1, U_endusers T2
										WHERE T1.enduserid = T2.sno AND sno = $enduserid AND ownerid = '". $_SESSION["ownerid"] ."'
										". $concatstr ." ORDER BY id DESC LIMIT $pager, 100");

									if($runningproc->num_rows)
									{
										?>
										<table class="table table-striped">
											<tr>
												<td style="width:1%;">
												</td>
												<td style="width:24%; text-align:center;">
													<b>Date</b>
												</td>
												<td style="width:24%; text-align:center;">
													<b>Login</b>
												</td>
												<td style="width:24%; text-align:center;">
													<b>Logout</b>
												</td>
												<td style="width:24%; text-align:center;">
													<b>Total</b>
												</td>
												<!-- <td style="width:24%; text-align:center;">
													Short
												</td> -->
											</tr>
										<?php
											$i=0;
											while($row = $runningproc->fetch_assoc())
											{
												?>
												<tr>
													<td style="text-align:center;"><?php echo $i+1; ?></td>
													<td style="text-align:center;"><?php echo Date("d-M-Y ( D )", strtotime($row["starttime"])); ?></td>
													<td style="text-align:center;"><?php echo Date("H:i", strtotime($row["starttime"])); ?></td>
													<td style="text-align:center;"><?php echo Date("H:i", strtotime($row["endtime"])); ?></td>
													<td style="text-align:center;">
														<?php
														$diff=strtotime($row["endtime"])-strtotime($row["starttime"]);
														echo converttotime($diff);
														?>
													</td>
												</tr>
												<?php
												$i++;
											}
											?>
										</table>
										<?php
									}
									else
									{
										?>
										No Records found
										<?php
									}
								?>
							</div>
							<div class="hide" id="pen3-left" style="float: left;height: auto;margin-left: 13px;width: 98%;">
							</div>
							<br class="clearfix">
						</div>
						<?php } else  {
							?>
							<div id="pen2" style="border:0;">
								<div id="pen2-left">
									<div class="col-md-12">
										<div class="col-md-4">
												<h3>Time Sheet</h3>
										</div>
										<div class="col-md-6 text-right" style="float:right;">
												<a href="javascript:void(0)" onclick="timesheetrefresh()" id="lazy-minutes-refresh" class="btn btn-primary btn-xs">Refresh <i class="glyphicon glyphicon-refresh"></i></a>
										</div>
									</div>
							 		<div class="clearfix"></div>
							 		<hr>
									<br>
									<form class="form-inline" method="GET" action="<?php echo baseurl; ?>timesheet.php">
										<select name="usersearch" id="usersearch" class="form-control">
										<?php
										//$userproc = $conn->query("select * from U_endusers where ownerid='".$_SESSION["ownerid"]."' AND active=1");
										$userproc = $conn->query("SELECT * FROM U_endusers WHERE ownerid = '".$_SESSION["ownerid"]."' AND active = 1 ORDER BY name ASC");

										echo "<option value=''>All</option>";
										if($userproc->num_rows)
										{
											while($userdetrow = $userproc->fetch_assoc())
											{
												?>
												<option value="<?php echo base64_encode($userdetrow['sno']); ?>" <?php if(base64_encode($userdetrow['sno']) == $_GET["usersearch"]) { echo "selected"; } ?>><?php echo $userdetrow["name"]; ?></option>
												<?php
											}
										}
										?>
										</select>
										<input type="text" id="runningappsdate"  class="form-control" name="selectedmonth" placeholder="Choose month" value="<?php if($_GET["selectedmonth"]!="") { echo $_GET["selectedmonth"]; } ?>">
										<!-- <input type="text" id="runningappsdate1"  class="form-control" name="enddate" placeholder="To Date" value="<?php if($_GET["enddate"]!="") { echo $_GET["enddate"]; } ?>"> -->
										<button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
									</form>
									<br><br>
									<script type="text/javascript">
										$(document).ready(function () {
											$('#toggle-view li').click(function () {
												var text = $(this).children('div.panel');
												if (text.is(':hidden')) {
													text.slideDown('200');
													$(this).children('span').html('-');
												} else {
													text.slideUp('200');
													$(this).children('span').html('+');
												}
											});
											$('#runningappsdate').datepicker({
							                        format: "M-yyyy",
												    minViewMode: 1,
												    maxViewMode: 1,
												    autoclose: true
							                });
							               /* $('#runningappsdate1').datepicker({
							                    format: "mm-yy",
											    minViewMode: 1,
											    maxViewMode: 1,
											    autoclose: true
							                });  */
										});
									</script>
									<?php
									if($_GET["selectedmonth"]!='')
									{
										$explodemonth = explode("-",$_GET["selectedmonth"]);
										$starttime=Date("Y-m-d H:i:s", strtotime($explodemonth["1"]."-".$explodemonth["0"]."-01 00:00:00"));
										$endtime=Date("Y-m-d H:i:s", strtotime($explodemonth["1"]."-".$explodemonth["0"]."-31 00:00:00"));
										$concatstr.= " AND (starttime between '".$starttime."' AND '".$endtime."')";
									}
									else{
										$starttime = Date("Y-m-01 00:00:00",time());
										$endtime = Date("Y-m-31 23:59:59",time());
										$concatstr.= " AND (starttime between '".$starttime."' AND '".$endtime."')";
									}
									if($_GET["usersearch"]!="")
									{
										$concatstr.= " AND enduserid='".base64_decode($_GET["usersearch"])."'";
									}

									$runningproc1 = $conn->query("select * from U_useratt as uatt INNER JOIN U_endusers as uend ON uatt.enduserid=uend.sno where uend.ownerid='".$_SESSION["ownerid"]."' ".$concatstr." order by id desc");
									$totalrecords = $runningproc1->num_rows;
									$total_pages = ceil($totalrecords/100);
									if(@$_REQUEST["pageno"]=="")
									{
											$_REQUEST["pageno"]="1";
											$pager=0;
									}
									else
									{
										$pager = (($_REQUEST["pageno"]*100)-100);
									}
									$count = (($_REQUEST["pageno"]*100)-100);
									$runningproc = $conn->query("select * from U_useratt as uatt INNER JOIN U_endusers as uend ON uatt.enduserid=uend.sno where uend.ownerid='".$_SESSION["ownerid"]."' ".$concatstr."order by id desc limit $pager,100");
									if($runningproc->num_rows)
									{
										?>
										<table class="table table-striped">
											<tr>
												<th style="text-align:center;">User</th>
												<th style="text-align:center;">Date</th>
												<th style="text-align:center;">Login</th>
												<th style="text-align:center;">Logout</th>
												<th style="text-align:center;">Total</th>
												<!-- <td style="width:24%; text-align:center;">Short</td> -->
											</tr>
										<?php
											$i=0;
											while($row = $runningproc->fetch_assoc())
											{
												?>
												<tr>
													<td style="line-height:30px;"><a href="javascript:void(0);" ng-click="showusertimesheet('<?php echo base64_encode($row["enduserid"]);?>','<?php echo $_GET["selectedmonth"];?>','<?php echo $row["name"]; ?>')" data-toggle="modal" data-target="#myModal"><?php echo getUserDet($row["enduserid"]); ?> (<?php echo $row["enduserid"]; ?>) </a></td>
													<td style="text-align:center;line-height: 30px;"><?php echo Date("d-M-Y ( D )", strtotime($row["starttime"])); ?></td>
													<td style="text-align:center;line-height: 30px;"><?php echo Date("H:i", strtotime($row["starttime"])); ?></td>
													<td style="text-align:center;line-height: 30px;"><?php echo Date("H:i", strtotime($row["endtime"])); ?></td>
													<td  class="text-center" style="line-height: 30px;">
														<?php
														$diff=strtotime($row["endtime"])-strtotime($row["starttime"]);
														$udiff[] = $diff;
														echo converttotime($diff);
														?>
													</td>
												</tr>
												<?php
												$i++;

											}
											?>
										<tr>
											<td colspan="4" class="text-center"><b>Average Working Hours</b></td>
											<td class="text-center">
												<?php
													$avgcnt = count($udiff);
													$sumarr = array_sum($udiff);
													$avgarr = ceil($sumarr/$avgcnt);
													echo converttotime($avgarr);
												?>
											</td>
										</tr>
										</table>
										<div style="align:center">
										<?php
										$page_url = baseurl ."timesheet.php?usersearch=".$_GET["usersearch"]."&selectedmonth=".$_GET["selectedmonth"];
										echo paginate("100", $_REQUEST["pageno"], $totalrecords,$total_pages , $page_url);
										?>
									</div>
										<?php
									}
									else
									{
										?>
										No Records found
										<?php
									}
								?>
								</div>
								<div id="myModal" class="modal fade" role="dialog">
								  <div class="modal-dialog">

								    <!-- Modal content-->
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal">&times;</button>
								        <h4 class="modal-title">Time Sheet <span ng-bind="endusername"></span></h4>
								      </div>
								      <div class="modal-body">
								        <p id="usertimesheet">

								        </p>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								      </div>
								    </div>

								  </div>
								</div>
							</div>
							<?php
						} ?>
						<!-- pagination -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php include("footer.php")?>
