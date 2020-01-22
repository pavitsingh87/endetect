<?php
$title = "Internet Usage | EnDetect";
include_once("commonfunctions.php");

if(isset($_GET['eid'])) {
	$enduserid = base64_decode($_GET["eid"]);
} else {
	$enduserid = "";
}

if(isset($_GET["rdate"])) {
	$rDate = $_GET["rdate"];
} else {
	$rDate = Date("M-Y", time());
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) .' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) .' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) .' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes .' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes .' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

include_once("header.php");
?>
<body ng-app="submitExample">
<?php include_once("headerbar.php"); ?>
	<div id="content-main" ng-controller="UserProfileController">
		<div class="row-body">
			<div id="pdata" style="margin-top: -65px;">
				<div id="resultval">
					<div id="content-main">
						<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
						<div id="pen1">
							<?php
							if($enduserid!="") {
								userprofile_account($enduserid);
								leftbar_withoutlive_account($enduserid);
								deleteUserModal();
							} else {
								homemenuoptions();
							}
							?>
							<br> <br>
						</div>
						<div id="pen2">
							<div id="pen2-left">
								<h3>Internet Usage</h3>
								<hr><br>
								<?php if(!isset($_GET["eid"])) { ?>
									<script type="text/javascript">
										$(document).ready(function () {
											$('#runningappsdate').datepicker({
							                    format: "M-yyyy",
											    minViewMode: 1,
											    maxViewMode: 1,
											    autoclose: true
							                });
										});
									</script>
									<form class="form-inline" method="GET" action="<?php echo baseurl; ?>internetusage.php">
										<input type="text" id="runningappsdate" class="form-control" name="rdate" placeholder="Date" value="<?php echo $rDate; ?>">
										<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
									</form>
									<br><br>
									<table class="table table-striped">
										<tr>
											<?php if( isset($_GET["l"]) && $_GET["l"] == "2") { ?>
												<th>Name</th>
											<?php } if(isset($_GET["l"])) { ?>
												<th>Date</th>
											<?php } ?>
											<th>Upload Data</th>
											<th>Download Data</th>
											<th>Total</th>
										</tr>
										<?php
										if (isset($_SESSION["ownerid"])) {
										    $concatstr = " AND U_endusers.ownerid='" . $_SESSION["ownerid"] . "'";
										}

										if (isset($_GET["rdate"])) {
										    $explodemonth = explode("-", $_GET["rdate"]);
										    $starttime    = Date("Y-m-d", strtotime($explodemonth["1"] . "-" . $explodemonth["0"] . "-01 00:00:00"));
										    $endtime      = Date("Y-m-d", strtotime($explodemonth["1"] . "-" . $explodemonth["0"] . "-31 00:00:00"));
										    $concatstr .= " AND (datesave between '" . $starttime . "' AND '" . $endtime . "')";
										} else {
										    $starttime = Date("Y-m-01", time());
										    $endtime   = Date("Y-m-31", time());
										    $concatstr .= " AND (datesave between '" . $starttime . "' AND '" . $endtime . "')";
										}

										if(!isset($_GET["l"])) {
											$lazyminutes = $conn->query("SELECT SUM(sent) as sent, SUM(received) as received FROM U_internetusage
												LEFT JOIN U_endusers ON U_internetusage.enduserid=U_endusers.sno
												WHERE 1 ".$concatstr." ORDER BY id DESC");
											if(($lazyminutes->num_rows) != 0) {
												while($row = $lazyminutes->fetch_assoc()) { ?>
													<tr>
														<td><?php echo formatSizeUnits($row["sent"]); ?></td>
														<td><?php echo formatSizeUnits($row["received"]); ?></td>
														<td><a href="internetusage.php?l=1&rdate=<?php echo $rDate; ?>">
															<?php echo formatSizeUnits($row["sent"] + $row["received"]); ?>
															<?php echo @$_REQUEST['q1'];?>
															<span class="glyphicon glyphicon-new-window"></span>
														</a></td>
													</tr>
													<?php
												}
											} else { ?>
												<tr>
													<td colspan="3"> No records</td>
												</tr>
												<?php
											}
										} else if(isset($_GET["l"]) && $_GET["l"] == "1") {
											$lazyminutes = $conn->query("SELECT SUM(sent) as sent, SUM(received) as received, datesave FROM U_internetusage
												LEFT JOIN U_endusers ON U_internetusage.enduserid=U_endusers.sno
												WHERE 1 ".$concatstr." GROUP BY datesave ORDER BY id DESC");
											if($lazyminutes->num_rows != 0) {
												while($row = $lazyminutes->fetch_assoc()) { ?>
													<tr>
														<td><a href="internetusage.php?l=2&cdate=<?php echo Date("d-M-Y", strtotime($row["datesave"])); ?>"><?php echo Date("d-M-Y", strtotime($row["datesave"])); ?></a></td>
														<td><?php echo formatSizeUnits($row["sent"]); ?></td>
														<td><?php echo formatSizeUnits($row["received"]); ?></td>
														<td><?php echo formatSizeUnits($row["sent"] + $row["received"]); ?></td>
													</tr>
													<?php
												}
											} else { ?>
												<tr>
													<td colspan="3"> No records</td>
												</tr>
												<?php
											}
										} else if(isset($_GET["l"]) && $_GET["l"] == 2) {
											if($_GET["cdate"]!="") {
												$starttime = Date("Y-m-d", strtotime($_GET["cdate"]));
												$endtime = Date("Y-m-d", strtotime($_GET["cdate"]));
												$concatstr .= " AND (datesave between '".$starttime."' AND '".$endtime."')";
											}

											$lazyminutes = $conn->query("SELECT U_internetusage.enduserid as enduserid,U_endusers.name as ename,U_internetusage.sent as sent,U_internetusage.received as received, U_internetusage.datesave as datesave FROM U_internetusage
												LEFT JOIN U_endusers ON U_internetusage.enduserid=U_endusers.sno
												WHERE 1 ".$concatstr." ORDER BY id DESC");
											if($lazyminutes->num_rows != 0) {
												while($row = $lazyminutes->fetch_assoc()) { ?>
													<tr>
														<td><?php echo $row["ename"]; ?></td>
														<td><?php echo Date("d-M-Y", strtotime($row["datesave"])); ?></td>
														<td><?php echo formatSizeUnits($row["sent"]); ?></td>
														<td><?php echo formatSizeUnits($row["received"]); ?></td>
														<td><?php echo formatSizeUnits($row["sent"] + $row["received"]); ?></td>
													</tr>
													<?php
												}
											} else { ?>
												<tr>
													<td colspan="5"> No records</td>
												</tr>
												<?php
											}
										} ?>
									</table>
								<?php } //if(!isset($_GET["eid"]))

								else { ?>
									<script type="text/javascript">
										$(document).ready(function () {
											$('#runningappsdate').datepicker({
							                    format: "M-yyyy",
											    minViewMode: 1,
											    maxViewMode: 1,
											    autoclose: true
							                });
										});
									</script>
									<form class="form-inline" method="GET" action="<?php echo baseurl; ?>internetusage.php">
										<input type="hidden" name="eid" id="eid" value="<?php echo $_GET["eid"]; ?>">
										<input type="text" id="runningappsdate"  class="form-control" name="rdate" placeholder="Date" value="<?php echo $rDate; ?>">
										<button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
									</form>
									<br><br>
									<table class="table table-striped">
										<tr>
											<?php if(isset($_GET["l"])) { ?>
												<th>Date</th>
											<?php } ?>
											<th>Upload Data</th>
											<th>Download Data</th>
											<th>Total Data</th>
										</tr>
										<?php
										if (isset($_SESSION["ownerid"])) {
										    $concatstr = " AND U_endusers.ownerid='" . $_SESSION["ownerid"] . "'";
										}

										if (isset($_GET["rdate"])) {
										    $explodemonth = explode("-", $_GET["rdate"]);
										    $starttime    = Date("Y-m-d", strtotime($explodemonth["1"] . "-" . $explodemonth["0"] . "-01 00:00:00"));
										    $endtime      = Date("Y-m-d", strtotime($explodemonth["1"] . "-" . $explodemonth["0"] . "-31 00:00:00"));
										    $concatstr .= " AND (datesave between '" . $starttime . "' AND '" . $endtime . "')";
										} else {
										    $starttime = Date("Y-m-01", time());
										    $endtime   = Date("Y-m-31", time());
										    $concatstr .= " AND (datesave between '" . $starttime . "' AND '" . $endtime . "')";
										}

										if (isset($_GET["eid"])) {
										    $concatstr .= " AND U_internetusage.enduserid ='". base64_decode($_GET["eid"]) ."'";
										}

										if(!isset($_GET["l"])) {
											$lazyminutes = $conn->query("SELECT SUM(sent) as sent, SUM(received) as received FROM U_internetusage
												LEFT JOIN U_endusers ON U_internetusage.enduserid=U_endusers.sno
												WHERE 1 ".$concatstr." ORDER BY id DESC");
											if($lazyminutes->num_rows != 0) {
												while($row = $lazyminutes->fetch_assoc()) { ?>
													<tr>
														<td><?php echo formatSizeUnits($row["sent"]); ?></td>
														<td><?php echo formatSizeUnits($row["received"]); ?></td>
														<td><a href="internetusage.php?eid=<?php echo $_GET["eid"]; ?>&l=1&rdate=<?php echo $rDate; ?>"><?php echo formatSizeUnits($row["sent"] + $row["received"]); ?> <?php echo $_REQUEST['q1'];?> <span class="glyphicon glyphicon-new-window"></span></a></td>
													</tr>
													<?php
												}
											} else { ?>
												<tr>
													<td colspan="3"> No records</td>
												</tr>
												<?php
											}
										} else if(isset($_GET["l"]) && $_GET["l"] == "1") {
											$lazyminutes = $conn->query("SELECT SUM(sent) as sent, SUM(received) as received,datesave FROM U_internetusage
												LEFT JOIN U_endusers ON U_internetusage.enduserid=U_endusers.sno
												WHERE 1 ".$concatstr." GROUP BY datesave ORDER BY id DESC");
											if($lazyminutes->num_rows != 0) {
												while($row = $lazyminutes->fetch_assoc()) { ?>
													<tr>
														<td><?php echo Date("d-M-Y", strtotime($row["datesave"])); ?></td>
														<td><?php echo formatSizeUnits($row["sent"]); ?></td>
														<td><?php echo formatSizeUnits($row["received"]); ?></td>
														<td><?php echo formatSizeUnits($row["sent"] + $row["received"]); ?></td>
													</tr>
													<?php
												}
											} else { ?>
												<tr>
													<td colspan="4"> No records</td>
												</tr>
												<?php
											}
										} ?>
									</table>
								<?php } ?>
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
<?php include_once("footer.php"); ?>
